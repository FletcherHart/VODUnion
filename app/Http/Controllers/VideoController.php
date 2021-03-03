<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Comment;
use App\Jobs\ProcessVideo;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class VideoController extends Controller
{

    public function __contstruct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Video::latest()->where('listed', 1)->get();

        return Inertia::render('Home', ['data'=> $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('store-video', Auth::user())) {
            return Redirect::route('upgrade');
        }
        
        $videos = Video::where([['user_id', Auth::user()->id],['status', '!=', 'done']])
            ->orderByDesc('created_at')
            ->get();

        foreach($videos as $key => $item) {
           $item = $this->status($item);
        };

        $videos = Video::where([['user_id', Auth::user()->id],['status', '=', 'done']])
            ->orderByDesc('created_at')
            ->get();
        
        return Inertia::render('Channel', ['videos' => $videos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('store-video', Auth::user())) {
            abort(403);
        } else {

            if(Video::where('user_id', Auth::user()->id)->count() >= 3 && Auth::user()->role->id < 4) {
                return Redirect::back()
                    ->withErrors(['deny' => 
                    'Error: You have reached the maximum allotment of video uploads, and cannot upload more at this time.'
                ]);
            }
            if($this->totalStorageUsed() >= 20000000000)
            {
                return Redirect::back()
                    ->withErrors(['deny' => 'Error: Max storage space occupied. No videos can be uploaded at this time.']);
            }
            if(Video::where('user_id', Auth::user()->id)->sum('sizeKB') >= 2000000000) {
                return Redirect::back()
                    ->withErrors(['deny' => 'Error: You have reached the maximum 2GB of storage per user.']);
            }

            $video = Video::where('user_id', Auth::user()->id)
                ->where('status', 'uploading')
                ->first();

            if($video != null) {
                if (Carbon::now()->gt(Carbon::parse($video->created_at)->add(5, 'minutes'))) {
                    $response = Http::withToken(config('app.cloud_token'))
                        ->delete('https://api.cloudflare.com/client/v4/accounts/'
                        . config('app.cloud_account') .
                        '/stream/' . $video->videoID);
                    $video->delete();
                    //$response = $this.getUploadUrl();
                }
            }

            if($video == null){

                $uploadURL = "";
                $uid = "";

                $response = $this->getUploadUrl();

                if($response['success']) {

                    $video = new Video;
                    $video->user_id = Auth::user()->id;
                    $video->videoID = $response['result']['uid'];
                    $video->uploadUrl = $response['result']['uploadURL'];
    
                    $video->save();    
                    
                } else {
                    return Redirect::back()
                        ->withInput()
                        ->withErrors(['deny' => 'Error: Cannot retrieve key.']);
                }
            }

            return Redirect::back()
                ->withInput()
                ->with('url', $video->uploadUrl);
            
        }
    }

    public function getUploadUrl() {
        $token = config('app.cloud_token');

        $account = config('app.cloud_account');

        $response = Http::withToken($token)
            ->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'POST',
                'Access-Control-Allow-Headers' => '*'
            ])
            ->post('https://api.cloudflare.com/client/v4/accounts/'.$account.'/stream/direct_upload', [
                "maxDurationSeconds" => 120,
                "expiry" => Carbon::now()->add(5, 'minutes')->toRfc3339String(),

            ]);
        
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Video::where('videos.id', $id)
            ->join('users', "videos.user_id", "users.id")
            ->first(['videos.title', 'videos.id', 'videos.videoID', 'videos.description', 'videos.created_at', 'users.name as uploader', 'videos.user_id']);
        
        $response = Http::withToken(config('app.cloud_token'))
            ->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'POST',
                'Access-Control-Allow-Headers' => '*'
            ])
            ->get('https://api.cloudflare.com/client/v4/accounts/'
            . config('app.cloud_account') .
            '/stream/analytics/views?metrics=totalImpressions&filters=videoId==' . $data['videoID'] . '&since=2021-01-01T00:00:00Z');

        $data['views'] = $response['result']['totals']['totalImpressions'];

        $comments = Comment::where('video_id', $id)
            ->join('users', "comments.user_id", "users.id")
            ->get(['comments.id','comments.text', 'comments.created_at as date', 'users.name', 'users.id as user_id']);

        $number_comments = Comment::where('video_id', $id)->count();
        return Inertia::render('Video', ['data'=> $data, 'comments'=>$comments, 'totalComments'=> $number_comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $user = Auth::user();

        if($video->user_id != $user->id) {
            return Redirect::route('home');
        }
        if($request->has('list')) {
            if($video->status != 'done') {
                return Redirect::back()
                    ->withErrors(['status' => 'Video must finish processing before it can be listed']);
            }

            if($request->has('title') || $video->title == null) {
                if($video->title == null || $video->title != $request['title'])
                {
                    $request->validate(['title' => 'required|max:80']);
                    $video->title = $request['title'];
                    $video->save();
                }
            }
            if($request->has('description') || $video->description == null) {
                if($video->description == null || $video->description != $request['description'])
                {
                    $request->validate(['description' => 'required|max:2500']);
                    $video->description = $request['description'];
                    $video->save();
                }
            }
            
            if($video->title != null && $video->description != null)
            {
                $video->listed = true;
            }
            else {
                $video->listed = false;
            }

            $video->save();
        } else {
            $request->validate([
                'title' => 'max:80',
                'description' => 'max:2500',
            ]);

            $video->title = $request['title'];
            $video->description = $request['description'];
            $video->save();
        }

        $request->validate([
            'thumbnail' => 'max:2000000|mimetypes:image/jpeg,image/png'
        ]);
        
        if($request->has('thumbnail')) { 
            $path = $request->file('thumbnail')->store('public/thumbnails');
            $video->thumbnail = $request->file('thumbnail')->hashname();
        }

        $video->save();

        return Redirect::back()
            ->with('updateStatus','Operation Completed')
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        if($video->user_id != Auth::user()->id) {
            return Redirect::route('home');
        }

        $response = Http::withToken(config('app.cloud_token'))
            ->delete('https://api.cloudflare.com/client/v4/accounts/'
            . config('app.cloud_account') .
            '/stream/' . $video->videoID);

        $video->delete();

        return Redirect::back();
    }

    public function totalStorageUsed() {
        return Video::all()->sum('sizeKB');
    }

    public function status(Video $video) {

        //$video = Video::where('videoID', $request['uid'])->first();
        if($video->status != 'done') {
            $response = Http::withToken(config('app.cloud_token'))
            ->get('https://api.cloudflare.com/client/v4/accounts/'
            . config('app.cloud_account') .
            '/stream/'. $video['videoID']);
            
            $status = $response['result']['status']['state'];

            if($status == 'ready') {
                $video->status = "done";
                $video->save();
                return $video;
            } else if ($status == 'inProgress') {
                $video->status = "pending";
                $video->save();
                return $video;
            }
        }

        return $video;
    }

    public function search($request) {
        $videos = Video::where('title', $request)
            ->orWhere('title', 'like', '%'.$request.'%')->get();

        return Inertia::render('Home.vue', ['data' => $videos, 'search' => $request]);
    }
}

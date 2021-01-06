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

        return Inertia::render('Dashboard', ['data'=> $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role->id == 1) {
            return Redirect::route('upgrade');
        }

        if(Video::where('user_id', Auth::user()->id)->count() >= 3 && Auth::user()->role->id < 4) {
            return Redirect::route('error')
                ->withErrors(['deny' => 
                'Error: You have reached the maximum allotment of video uploads, and cannot upload more at this time.'
            ]);
        }
        if($this->totalStorageUsed() >= 20000000000)
        {
            return Redirect::route('error')
                ->withErrors(['deny' => 'Error: Max storage space occupied. No videos can be uploaded at this time.']);
        }
        if(Video::where('user_id', Auth::user()->id)->sum('sizeKB') >= 2000000000) {
            return Redirect::route('error')
                ->withErrors(['deny' => 'Error: You have reached the maximum 2GB of storage per user.']);
        }

        return Inertia::render('Upload');
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

            $uploadURL = "";
            $uid = "";

            if($response['success']) {

                $video = new Video;
                $video->user_id = Auth::user()->id;
                $video->videoID = $response['result']['uid'];
                $video->uploadUrl = $response['result']['uploadURL'];

                $video->save();

                return [$response['result']['uid'], $response['result']['uploadURL']];

                return Redirect::route('upload')
                    ->withInput()
                    ->with('stuff', 'Hello');

                return Redirect::route('upload')
                    ->withInput()
                    ->with('data', ['uid' => $response['result']['uid'], 'url' => $response['result']['uploadURL']]);
            } else {
                return Redirect::route('upload')
                    ->withInput()
                    ->withErrors(['key' => 'Error: Cannot retrieve key.']);
            }
            

            // $validator = $request->validate([
            //     'title' => 'required|max:80',
            //     'description' => 'required|max:2500',
            //     'video' => 'required|max:200000000|mimetypes:video/mp4',
            //     'listed' => 'required',
            //     'thumbnail' => 'max:2000000|mimetypes:image/jpeg,image/png'
            // ]);
            
            // $hasThumb = false;
            // if($request->has('thumbnail')) {
            //     $path = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('video')->hashName() . '.jpeg');

            //     if($path === false) {
            //         return Redirect::route('upload')
            //             ->withErrors(['storage' => 'Error: Thumbnail could not be stored. Please try again later.'])
            //             ->withInput();
            //     }
            //     $hasThumb = true;
            // }

            // $video = new Video;
            // $video->title = $request->title;
            // $video->description = $request->description;
            // //$video->listed = $request->listed;
            // $video->user_id = Auth::user()->id;
            // //$video->storedAt = $request->file('video')->hashName();
            // $video->sizeKB = $request->file('video')->getSize();

            // $video->save();

        }
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
            ->first(['videos.title', 'videos.id', 'videos.videoID', 'videos.description', 'videos.created_at', 'users.name as uploader']);
        
        $response = Http::withToken(config('app.cloud_token'))
            ->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'POST',
                'Access-Control-Allow-Headers' => '*'
            ])
            ->get('https://api.cloudflare.com/client/v4/accounts/'
            . config('app.cloud_account') .
            '/stream/analytics/views?metrics=totalImpressions&filters=videoId==' . $data['videoID']);

        $data['views'] = $response['result']['totals']['totalImpressions'];

        $comments = Comment::where('video_id', $id)
            ->join('users', "comments.user_id", "users.id")
            ->get(['comments.text', 'comments.created_at as date', 'users.name', 'users.id as user_id']);
        return Inertia::render('Video', ['data'=> $data, 'comments'=>$comments]);
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
            if($video->title == null)
                $request->validate(['title' => 'required|max:80']);
            if($video->description == null)
                $request->validate(['description' => 'required|max:2500']);
        } else {
            $request->validate([
                'title' => 'max:80',
                'description' => 'max:2500',
            ]);
        }

        $request->validate([
            'thumbnail' => 'max:2000000|mimetypes:image/jpeg,image/png'
        ]);
        
        
        if($request->has('title'))
            $video->title = $request['title'];
        if($request->has('description'))
            $video->description =  $request['description'];
        if($request->has('thumbnail')) { 
            $path = $request->file('thumbnail')->store('public/thumbnails');
            $video->thumbnail = $request->file('thumbnail')->hashname();
        }
        if($request->has('list'))
            $video->listed = true;
        else 
            $video->listed = false;

        $video->save();

        return Redirect::back()
            ->with('updateStatus','Operation Completed');
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

    public function userVideos() {
        $user = Auth::user();

        if (! Gate::allows('store-video', $user)) {
            return Redirect::route('upgrade');
        }
        
        $videos = Video::where('user_id', Auth::user()->id)
            ->get();

        $videos->each(function($item, $key) {
           $item = $this->status($item);
        });
        
        return Inertia::render('Channel', ['data' => $videos]);
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
            } else if ($status = 'inProgress') {
                $video->status = "pending";
                $video->save();
                return $video;
            }
        }

        return $video;
    }
}

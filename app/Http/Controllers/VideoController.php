<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\LikeVideo;
use App\Models\WatchedVideo;
use App\Models\Comment;
use App\Jobs\UpdateVideoViews;
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
            $data = $this->getVideos(['listed', 1], 
                ['videos.title', 'videos.id', 'videos.videoID', 'videos.views', 'videos.created_at', 'users.name as uploader', 'videos.user_id', 'videos.video_length']
                , ['users', "videos.user_id", "users.id"]
            );
        return Inertia::render('Home', ['data'=> $data]);
    }

    /*
     * Get videos function
     * @return array
    */
    public function getVideos($conditions, $get_params = '*', $join_params = null) {
        if($join_params == null || count($join_params) < 2) {
            return Video::where($conditions)
            ->orderByDesc('created_at')
            ->get($get_params);
        } else {
            return Video::where(...$conditions)
            ->join(...$join_params)
            ->orderByDesc('created_at')
            ->get($get_params);
        }
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
        
        $videos = $this->getVideos([['user_id', Auth::user()->id],['status', '!=', 'ready']]);

        foreach($videos as $key => $item) {
            //On first pass video will have status of readyToUpload to skip first pendingupload check
            if($item->status == "pendingupload") {
                //Check if video is still pending upload, delete if so
                $item = $this->status($item);
                if($item->status == "pendingupload") {
                    $this->destroy($item);
                }
                
            } elseif ($item->status == "error"){
                $this->destroy($item);
            } else {
                $item = $this->status($item);
            }
           
        };

        $videos = $this->getVideos([['user_id', Auth::user()->id],['video_length', '<=', '0']]);

        $videos->each(function ($collection, $alphabet) {
            $response = Http::withToken(config('app.cloud_token'))
            ->get('https://api.cloudflare.com/client/v4/accounts/'
            . config('app.cloud_account') .
            '/stream/'. $collection['videoID']);
            $collection['video_length'] = $response['result']['duration'];
            $collection->save();
        });
        
        $videos = $this->getVideos([['user_id', Auth::user()->id],['status', '=', 'ready']]);
        $videos2 = $this->getVideos([['user_id', Auth::user()->id],['status', '=', 'processing']]);
        $videos = $videos->merge($videos2);

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

            $response = $this->getUploadUrl();

            if($response['success']) {

                $video = new Video;
                $video->user_id = Auth::user()->id;
                $video->videoID = $response['result']['uid'];
                $video->uploadUrl = $response['result']['uploadURL'];
                $video->status = "readyToUpload";

                $video->save();    
                
            } else {
                return Redirect::back()
                    ->withInput()
                    ->withErrors(['deny' => 'Error: Cannot retrieve key.']);
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

        if(auth()->id() != null && WatchedVideo::where('user_id', auth()->id())->where('video_id', $id)->first() == null) {
            $watched = new WatchedVideo;
            $watched->user_id = auth()->id();
            $watched->video_id = $id;
            $watched->save();
        }

        UpdateVideoViews::dispatch($id)->delay(now()->addMinutes(10));
        
        $data = $this->getVideos(['videos.id', $id], 
            ['videos.title', 'videos.id', 'videos.videoID', 'videos.description', 'videos.views', 'videos.created_at', 'users.name as uploader', 'videos.user_id']
            , ['users', 'videos.user_id', 'users.id'])[0];

        $comments = Comment::where('video_id', $id)
            ->join('users', "comments.user_id", "users.id")
            ->get(['comments.id','comments.text', 'comments.created_at as date', 'users.name', 'users.id as user_id']);

        $isLiked = LikeVideo::where(['user_id' => auth()->id(), 'video_id' => $id])->first('like');

        $numLikes = LikeVideo::where(['video_id' => $id, 'like'=>1])->count();
        $numDislikes = LikeVideo::where(['video_id' => $id, 'like'=>0])->count();

        if($isLiked == null) {
            $isLiked = 2;
        } else {
            $isLiked = $isLiked->like;
        }

        $number_comments = Comment::where('video_id', $id)->count();
        return Inertia::render('Video', ['data'=> $data, 'comments'=>$comments, 'totalComments'=> $number_comments, 'isLiked'=>$isLiked, 'likes'=>$numLikes, 'dislikes'=>$numDislikes]);
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
    public function update(Request $request, Video $video){
        $user = Auth::user();

        if($video->user_id != $user->id) {
            return Redirect::route('home');
        }
        if($request->has('list')) {
            if($video->status != 'ready') {
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
    public function destroy(Video $video){
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

    public function status(Video $video) {
        if($video->status != 'ready') {
            $response = Http::withToken(config('app.cloud_token'))
            ->get('https://api.cloudflare.com/client/v4/accounts/'
            . config('app.cloud_account') .
            '/stream/'. $video['videoID']);

            $video->status = $response['result']['status']['state'];
            $video->save();
            return $video;
        }

        return $video;
    }

    public function search($request) {
        $videos = Video::where('title', $request)
            ->orWhere('title', 'like', '%'.$request.'%')->get();

        return Inertia::render('Home.vue', ['data' => $videos, 'search' => $request]);
    }
}

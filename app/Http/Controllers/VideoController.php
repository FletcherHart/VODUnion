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
        $data = Video::latest()->get();

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
        } else {
            return Inertia::render('Upload');
        }
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
            if(Video::where('user_id', Auth::user()->id)->count() >= 3) {
                return Redirect::route('upload')
                    ->withErrors(['storage' => 'Error: You have reached the maximum allotment of video uploads.'])
                    ->withInput();
            }
            if(Video::where('user_id', Auth::user()->id)->sum('sizeKB') >= 2000000000) {
                return Redirect::route('upload')
                    ->withErrors(['storage' => 'Error: You have reached the maximum 2GB of storage per user.'])
                    ->withInput();
            }

            $validator = $request->validate([
                'title' => 'required|max:80',
                'description' => 'required|max:2500',
                'video' => 'required|max:200000000|mimetypes:video/mp4',
                'listed' => 'required',
                'thumbnail' => 'max:2000000|mimetypes:image/jpeg,image/png'
            ]);

            if($this->totalStorageUsed() >= 20000000000)
            {
                return Redirect::route('upload')
                    ->withErrors(['storage' => 'Error: Max storage space occupied. No videos can be uploaded at this time.'])
                    ->withInput();
            }
            if($request->has('raw')) {
                $path = $request->file('video')->store('public/stream');
            } else {
                $path = $request->file('video')->store('public/videos');
            }

            if($path === false) {
                return Redirect::route('upload')
                    ->withErrors(['storage' => 'Error: Video could not be stored. Please try again later.'])
                    ->withInput();
            }

            $hasThumb = false;
            if($request->has('thumbnail')) {
                $path = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('video')->hashName() . '.jpeg');

                if($path === false) {
                    return Redirect::route('upload')
                        ->withErrors(['storage' => 'Error: Thumbnail could not be stored. Please try again later.'])
                        ->withInput();
                }
                $hasThumb = true;
            }

            $video = new Video;
            $video->title = $request->title;
            $video->description = $request->description;
            //$video->listed = $request->listed;
            $video->user_id = Auth::user()->id;
            $video->storedAt = $request->file('video')->hashName();
            $video->sizeKB = $request->file('video')->getSize();

            $video->save();

            $raw = false;

            if($request->has('raw')) {
                $raw = true;
                dump('Raw video uploaded');
            }

            ProcessVideo::dispatch($video, $hasThumb, $raw);

            return $path;
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
            ->first(['videos.title', 'videos.id', 'videos.description', 'videos.views', 'videos.storedAt', 'videos.created_at', 'users.name as uploader']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }

    public function totalStorageUsed() {
        return Video::all()->sum('sizeKB');
    }
}

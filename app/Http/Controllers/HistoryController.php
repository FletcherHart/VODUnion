<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Video;
use App\Models\WatchedVideo;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function watchedVideos(Request $request) {

        $videos_to_get = 12;
        $max_videos = WatchedVideo::where('watched_videos.user_id', '=', auth()->id())->count();
        
        if($request['num_videos'] < $max_videos && $request['num_videos'] != null) {
            $videos_to_get += $request['num_videos'];
        }

        $videos = Video::join('watched_videos', 'videos.id', '=', 'watched_videos.video_id')
            ->join('users', 'videos.user_id', '=', 'users.id')
            ->where('watched_videos.user_id', '=', auth()->id())
            ->select('videos.*', 'users.name as uploader')
            ->take($videos_to_get)
            ->get();

        return Inertia::render('History', ['videos' => $videos, 'maxVideos'=>$max_videos]);
    }

    public function comments() {
        $comments = Comment::where('comments.user_id', '=', auth()->id())
        ->join('videos', 'videos.id', '=', 'comments.video_id')
        ->orderBy('comments.created_at','DESC')
        ->get(['comments.*', 'videos.id as video_id', 'videos.title']);

        return Inertia::render('History', ['comments'=>$comments]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function watchedVideos(Request $request) {

        $videos_to_get = 12;
        $max_videos = DB::table('watched_videos')->where('watched_videos.user_id', '=', auth()->id())->count();
        
        if($request['num_videos'] < $max_videos && $request['num_videos'] != null) {
            $videos_to_get += $request['num_videos'];
        }

        $videos = DB::table('videos')
            ->join('watched_videos', 'videos.id', '=', 'watched_videos.video_id')
            ->join('users', 'videos.user_id', '=', 'users.id')
            ->where('watched_videos.user_id', '=', auth()->id())
            ->select('videos.*', 'users.name as uploader')
            ->take($videos_to_get)
            ->get();

        return Inertia::render('History', ['videos' => $videos, 'maxVideos'=>$max_videos]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function watchedVideos() {
        $videos = DB::table('videos')
            ->join('watched_videos', 'videos.id', '=', 'watched_videos.video_id')
            ->join('users', 'videos.user_id', '=', 'users.id')
            ->where('watched_videos.user_id', '=', auth()->id())
            ->select('videos.*', 'users.name as uploader')
            ->get();

        return Inertia::render('History', ['videos' => $videos]);
    }
}

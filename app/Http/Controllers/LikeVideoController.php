<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\LikeVideo;
use App\Jobs\ProcessVideo;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class LikeVideoController extends Controller
{
    public function like(Video $video) {
        $like = LikeVideo::where(['user_id' => auth()->id(), 'video_id' => $video->id])->first();
        if($like != null) {
            if($like->like == true) {
                $this->unlikeOrUndislike($video);
                return redirect()->back();
            } else {
                $like->like = true;
                $like->save();
                return redirect()->back();
            }
        }

        $like = new LikeVideo;
        $like->user_id = auth()->id();
        $like->video_id = $video->id;
        $like->save();
        return redirect()->back();
    }

    public function unlikeOrUndislike(Video $video) {
        LikeVideo::where(['user_id' => auth()->id(), 'video_id' => $video->id])->delete();
        return;
    }

    public function dislike(Video $video) {
        $dislike = LikeVideo::where(['user_id' => auth()->id(), 'video_id' => $video->id])->first();
        if($dislike != null) {
            if($dislike->like == 0) {
                $this->unlikeOrUndislike($video);
                return redirect()->back();
            } else {
                $dislike->like = 0;
                $dislike->save();
                return redirect()->back();
            }
        }

        $dislike = new LikeVideo;
        $dislike->user_id = auth()->id();
        $dislike->video_id = $video->id;
        $dislike->like = false;
        $dislike->save();
        return redirect()->back();
    }

}
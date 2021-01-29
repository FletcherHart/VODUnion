<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpgradeCode;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    public function index() {
        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        return Inertia::render('AdminPanel');
    }

    public function deleteVideo(Video $video) {

        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        $response = Http::withToken(config('app.cloud_token'))
            ->delete('https://api.cloudflare.com/client/v4/accounts/'
            . config('app.cloud_account') .
            '/stream/' . $video->videoID);

        $video->delete();

        return Redirect::back();
    }

    public function listUsers() {
        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        $users = User::all();

        return Inertia::render('AdminPanel', ['users' => $users]);
    }

    public function listVideos() {
        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        //$videos = Video::all();
        $videos = Video::join('users', 'videos.user_id', '=', 'users.id')
            ->get(['videos.*', 'users.name as username']);

        return Inertia::render('AdminPanel', ['videos' => $videos]);
    }
    
    public function listKeys() {

        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        $data = UpgradeCode::get();

        return Inertia::render('UpgradeKeys', ['upgradeKeys'=> $data]);
    }

    public function generateKeys() {

        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        for($i = 0; $i < 10; $i++) {
            $code = new UpgradeCode;
            $code->timestamps = false;
            $code->upgrade_code = hash('md5', Str::random(10));
            $code->save();
        }

        return redirect()->back();
    }

    public function banUser(User $user) {

        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        $user->banned = 1;
        $user->ban_date = Carbon::now();
        $user->save();
    }

}

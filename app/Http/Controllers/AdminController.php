<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpgradeCode;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index() {
        if (! Gate::allows('admin', Auth::user())) {
            return Redirect::route('home');
        }

        return Inertia::render('AdminPanel');
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

        DB::table('banned_users')->insertOrIgnore([
            ['user_id' => $user->id]
        ]);
    }

}

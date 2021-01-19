<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpgradeCode;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Str;

class UpgradeCodeController extends Controller
{

    public function index() {

        if (! Gate::allows('upgrade-key', Auth::user())) {
            return Redirect::route('home');
        }

        $data = UpgradeCode::get();

        return Inertia::render('UpgradeKeys', ['upgradeKeys'=> $data]);
    }

    public function generate() {

        if (! Gate::allows('upgrade-key', Auth::user())) {
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

    public function store(Request $request) {
        $request->validate(['code' => 'required|max:50']);

        $code = UpgradeCode::where('upgrade_code', $request['code'])->first();

        if($code != null) {
            Auth::user()->role_id = 2;
            Auth::user()->save();
            $code->delete();

            return redirect()->route('home');
        } else {
            return redirect()->back()->withErrors(['code' => 'Code is invalid. Please use a valid code.']);
        }
    }

    public function destroy(UpgradeCode $code) {

    }
}

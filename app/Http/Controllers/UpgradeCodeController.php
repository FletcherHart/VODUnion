<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpgradeCode;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class UpgradeCodeController extends Controller
{
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

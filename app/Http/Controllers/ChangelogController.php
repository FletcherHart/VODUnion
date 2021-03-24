<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Changelog;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ChangelogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::allows('admin', auth()->user())) {
            $request->validate(['title'=>'required','text' => 'required']);

            $change = new Changelog;
            $change->title = $request['title'];
            $change->text = $request['text'];
            $change->user_id = auth()->id();
    
            $change->save();
        }
        
    }

    public function create(Request $request)
    {
        if (Gate::allows('admin', auth()->user())) {
            return Inertia::render('ChangelogForm');
        }

        return redirect()->route('home');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Changelog;

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
        $request->validate(['title'=>'required','text' => 'required']);

        $change = new Changelog;
        $change->title = $request['title'];
        $change->text = $request['text'];
        $change->user_id = auth()->id();

        $change->save();

        return redirect()->route('video', ['id' => $video->id]);
    }
}

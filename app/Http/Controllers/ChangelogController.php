<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Changelog;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ChangelogController extends Controller
{
    public function index() {
        $changelog = Changelog::orderBy('created_at', 'DESC')->first();
        $past_logs = Changelog::where('id', '!=', $changelog->id)->get(['id','title', 'created_at', 'updated_at']);

        return Inertia::render('Changelog/ChangelogIndex', ['changelog' => $changelog, 'past_logs' => $past_logs]);
    }

    public function show() {
        
    }

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
            return Inertia::render('Changelog/ChangelogForm');
        }

        return redirect()->route('home');
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;
use App\Models\Video;
use Iman\Streamer\VideoStreamer;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [VideoController::class, 'index'])
    ->name('home');

Route::get('/video/{id}', [VideoController::class, 'show'])
    ->name('video');

Route::post('/video/{video}/comment', [CommentController::class, 'store'])
    ->middleware('auth');

Route::get('/upload', function() {
    return Redirect::route('channel');
});

Route::get('/key', [VideoController::class, 'store']);

Route::get('/upgrade', function() {
    return Inertia::render('Upgrade');
})->middleware('auth')->name('upgrade');

// Route::middleware(['auth:sanctum', 'verified'])->get('/', [VideoController::class, 'index'])
//     ->name('dashboard');

Route::get('/stream/{id}', function($id) {
    $video = Video::where('videos.id', $id)->first('storedAt');
    $path = public_path('/storage/stream/'.$video['storedAt']);
    
    VideoStreamer::streamFile($path);
});

Route::get('error', function() {
    return Inertia::render('Error');
})->name('error');

//Channel page routes
// Route::get('/channel', [UserController::class, "index"])
//     ->middleware('auth');
Route::get('/channel', [VideoController::class, "userVideos"])
     ->middleware('auth')->name('channel');

// Route::get('/channel/{id}', [UserController::class, "show"])
//     ->middleware('auth');

Route::post('/channel/{video}', [VideoController::class, "update"])
    ->middleware('auth');

Route::delete('/channel/{video}', [VideoController::class, "destroy"])
    ->middleware('auth');

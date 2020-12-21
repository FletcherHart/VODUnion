<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;
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

Route::get('/', [VideoController::class, 'index']);

Route::get('/video/{id}', [VideoController::class, 'show'])
    ->name('video');

Route::post('/video/{video}/comment', [CommentController::class, 'store'])
    ->middleware('auth');

Route::post('/upload', [VideoController::class, 'store'])
    ->middleware('auth');

Route::get('/upload', [VideoController::class, 'create'])
    ->name('upload')->middleware('auth');

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
    Inertia::render('Error');
})->name('error');



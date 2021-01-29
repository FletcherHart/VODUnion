<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Inertia\Inertia;
use App\Models\Video;
use App\Models\UpgradeCode;
use Iman\Streamer\VideoStreamer;
use Illuminate\Http\Request;

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

Route::post('/key', [VideoController::class, 'store']);

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
Route::get('/channel', [VideoController::class, "create"])
     ->middleware('auth')->name('channel');

// Route::get('/channel/{id}', [UserController::class, "show"])
//     ->middleware('auth');

Route::post('/channel/{video}', [VideoController::class, "update"])
    ->middleware('auth');

Route::delete('/channel/{video}', [VideoController::class, "destroy"])
    ->middleware('auth');

//Upgrade route
Route::get('/upgrade', function() {
    return Inertia::render('Upgrade');
})->middleware('auth')->name('upgrade');

Route::post('/upgrade', function(Request $request) {
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
})->middleware('auth');

//Search routes
Route::get('search/{term}', [VideoController::class, "search"]);
Route::get('search', function() {
    return redirect()->route('home');
});

//Admin routes
Route::get('/admin/', [AdminController::class, 'index'])
    ->middleware('auth')->name('adminPanel');

Route::post('/admin/upgradeKeys', [AdminController::class, 'generateKeys']);

Route::get('/admin/upgradeKeys', [AdminController::class, 'listKeys'])
    ->middleware('auth')->name('upgradeKeys');

Route::post('/admin/ban/{user}', [AdminController::class, 'banUser']);

Route::get('/admin/listUsers', [AdminController::class, 'listUsers']);

Route::get('/admin/listVideos', [AdminController::class, 'listVideos']);

Route::post('/admin/deleteVideo/{video}', [AdminController::class, 'deleteVideo']);
<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if ($team = request()->user()->currentTeam) {
            return redirect()->route('buckets.posts.index', $team->bucket);
        }

        return view('dashboard');
    })->name('dashboard');

    Route::resource('buckets.posts', Controllers\BucketPostsController::class)->parameters(['posts' => 'recording']);
    Route::resource('recordings.comments', Controllers\RecordingCommentsController::class)->only(['index', 'create', 'store']);
    Route::resource('comments', Controllers\CommentsController::class)->parameters(['comments' => 'recording'])->only(['show', 'edit', 'update', 'destroy']);
});

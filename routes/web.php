<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (($team = request()->user()->currentTeam) && $team->bucket->recordings()->blog()->exists()) {
            return redirect()->route('buckets.blogs.posts.index', [$team->bucket, $team->bucket->recordings()->blog()->first()]);
        }

        return view('dashboard', [
            'bucket' => $team->bucket,
            'blog' => $team->bucket->recordings()->blog()->first(),
        ]);
    })->name('dashboard');

    Route::resource('buckets.blogs', Controllers\BucketBlogsController::class)->only(['show']);
    Route::resource('buckets.blogs.posts', Controllers\BucketBlogPostsController::class)->only(['index', 'create', 'store']);
    Route::resource('buckets.posts', Controllers\BucketPostscontroller::class)->only(['show', 'edit', 'update', 'destroy']);
    Route::resource('buckets.recordings.comments', Controllers\BucketRecordingCommentsController::class)->only(['index', 'create', 'store']);
    Route::resource('buckets.comments', Controllers\BucketCommentsController::class)->only(['show', 'edit', 'update', 'destroy']);
});

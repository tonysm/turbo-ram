<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'bucket' => $bucket = auth()->user()->currentTeam->bucket,
            'blog' => $bucket->recordings()->blog()->first(),
        ]);
    })->name('dashboard');

    Route::resource('buckets.blogs', Controllers\BucketBlogsController::class)->only(['show']);
    Route::resource('buckets.blogs.posts', Controllers\BucketBlogPostsController::class)->only(['index', 'create', 'store']);
    Route::resource('buckets.posts', Controllers\BucketPostsController::class)->only(['show', 'edit', 'update', 'destroy']);
    Route::resource('buckets.recordings.comments', Controllers\BucketRecordingCommentsController::class)->only(['index', 'create', 'store']);
    Route::resource('buckets.comments', Controllers\BucketCommentsController::class)->only(['show', 'edit', 'update', 'destroy']);
});

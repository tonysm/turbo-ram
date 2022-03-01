<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Post;
use App\Models\Recording;
use Illuminate\Http\Request;

class BucketPostsController extends Controller
{
    public function store(Request $request, Bucket $bucket)
    {
        $this->authorize('addPost', $bucket);

        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
        ]);

        $recording = $bucket->record($this->newPost($request));

        return redirect()->route('buckets.posts.show', [$bucket, $recording]);
    }

    public function update(Request $request, Bucket $bucket, Recording $recording)
    {
        $this->authorize('update', $recording);

        $recording->update([
            'recordable' => tap($this->newPost($request))->save(),
        ]);

        return to_route('buckets.posts.show', [$bucket, $recording]);
    }

    private function newPost(Request $request): Post
    {
        return new Post($request->validate([
            'title' => ['required', 'max:255'],
            'content' => ['required'],
        ]));
    }
}

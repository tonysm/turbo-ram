<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Post;
use App\Models\Recording;
use Illuminate\Http\Request;

class BucketPostsController extends Controller
{
    public function show(Request $request, Bucket $bucket, Recording $post)
    {
        $this->authorize('view', $post);

        return view('bucket_posts.show', [
            'bucket' => $bucket,
            'recording' => tap($post, function ($recording) use ($request) {
                if ($request->old('title') || $request->old('content')) {
                    $recording->setRelation('recordable', $this->newPost($request, required: false));
                }
            }),
            'comments' => $post->children()
                ->oldest()
                ->get(),
        ]);
    }

    public function edit(Request $request, Bucket $bucket, Recording $post)
    {
        $this->authorize('update', $post);

        return view('bucket_posts.edit', [
            'bucket' => $bucket,
            'recording' => tap($post, function ($recording) use ($request) {
                if ($request->old('title') || $request->old('content')) {
                    $recording->setRelation('recordable', $this->newPost($request, required: false));
                }
            }),
        ]);
    }

    public function update(Request $request, Bucket $bucket, Recording $post)
    {
        $this->authorize('update', $post);

        $post->update([
            'recordable' => tap($this->newPost($request))->save(),
        ]);

        return to_route('buckets.posts.show', [$bucket, $post]);
    }

    public function destroy(Bucket $bucket, Recording $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return redirect()
            ->route('buckets.blogs.show', [$bucket, $post->parent])
            ->with('status', 'Post was deleted');
    }

    private function newPost(Request $request, bool $required = true): Post
    {
        return new Post($request->validate([
            'title' => [$required ? 'required' : 'sometimes', 'max:255'],
            'content' => [$required ? 'required' : 'sometimes'],
        ]));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Post;
use App\Models\Recording;
use Illuminate\Http\Request;

class BucketPostsController extends Controller
{
    public function index(Bucket $bucket)
    {
        $this->authorize('view', $bucket);

        return view('bucket_posts.index', [
            'bucket' => $bucket,
            'posts' => $bucket->recordings()
                ->posts()
                ->latest('id')
                ->cursorPaginate(15),
        ]);
    }

    public function create(Request $request, Bucket $bucket)
    {
        $this->authorize('addPost', $bucket);

        return view('bucket_posts.create', [
            'bucket' => $bucket,
            'recording' => $bucket->recordings()->make()->setRelation('recordable', $this->newPost($request, required: false)),
        ]);
    }

    public function show(Bucket $bucket, Recording $recording)
    {
        $this->authorize('view', $recording);

        return view('bucket_posts.show', [
            'bucket' => $bucket,
            'recording' => $recording,
        ]);
    }

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

    public function edit(Request $request, Bucket $bucket, Recording $recording)
    {
        $this->authorize('update', $recording);

        return view('bucket_posts.edit', [
            'bucket' => $bucket,
            'recording' => tap($recording, function ($recording) use ($request) {
                if ($request->old('title') || $request->old('content')) {
                    $recording->setRelation('recordable', $this->newPost($request, required: false));
                }
            }),
        ]);
    }

    public function update(Request $request, Bucket $bucket, Recording $recording)
    {
        $this->authorize('update', $recording);

        $recording->update([
            'recordable' => tap($this->newPost($request))->save(),
        ]);

        return to_route('buckets.posts.show', [$bucket, $recording]);
    }

    public function destroy(Bucket $bucket, Recording $recording)
    {
        $this->authorize('destroy', $recording);

        $recording->delete();

        return redirect()->route('dashboard')->with('status', 'Post was deleted');
    }

    private function newPost(Request $request, bool $required = true): Post
    {
        return new Post($request->validate([
            'title' => [$required ? 'required' : 'sometimes', 'max:255'],
            'content' => [$required ? 'required' : 'sometimes'],
        ]));
    }
}

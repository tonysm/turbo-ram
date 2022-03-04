<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Post;
use App\Models\Recording;
use Illuminate\Http\Request;

class BucketBlogPostsController extends Controller
{
    public function index(Bucket $bucket, Recording $blog)
    {
        $this->authorize('view', $bucket);

        return view('bucket_posts.index', [
            'bucket' => $bucket,
            'blog' => $blog,
            'posts' => $blog->children()
                ->latest('id')
                ->cursorPaginate(15),
        ]);
    }

    public function create(Request $request, Bucket $bucket, Recording $blog)
    {
        $this->authorize('addPost', $bucket);

        return view('bucket_posts.create', [
            'bucket' => $bucket,
            'blog' => $blog,
            'recording' => $bucket->recordings()->make()
                ->setRelation('recordable', $this->newPost($request, required: false))
                ->setRelation('parent', $blog)
        ]);
    }

    public function store(Request $request, Bucket $bucket, Recording $blog)
    {
        $this->authorize('addPost', $bucket);

        $post = $bucket->record(
            recordable: $this->newPost($request),
            parent: $blog,
        );

        return redirect()->route('buckets.posts.show', [$bucket, $post]);
    }

    public function newPost(Request $request, bool $required = true): Post
    {
        return new Post($request->validate([
            'title' => [$required ? 'required' : 'sometimes', 'max:255'],
            'content' => [$required ? 'required' : 'sometimes'],
        ]));
    }
}

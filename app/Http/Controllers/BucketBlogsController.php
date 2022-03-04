<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Recording;

class BucketBlogsController extends Controller
{
    public function show(Bucket $bucket, Recording $blog)
    {
        $this->authorize('view', $blog);

        return view('blogs.show', [
            'bucket' => $bucket,
            'blog' => $blog,
            'posts' => $blog->children()
                ->latest()
                ->cursorPaginate(),
        ]);
    }
}

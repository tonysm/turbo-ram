<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Comment;
use App\Models\Recording;
use Illuminate\Http\Request;

class BucketCommentsController extends Controller
{
    public function show(Bucket $bucket, Recording $comment)
    {
        return view('comments.show', [
            'bucket' => $bucket,
            'recording' => $comment,
        ]);
    }

    public function edit(Request $request, Bucket $bucket, Recording $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', [
            'bucket' => $bucket,
            'recording' => tap($comment, function ($commentRecording) use ($request) {
                if ($request->old('content')) {
                    $commentRecording->setRelation('recordable', $this->newComment($request, required: false));
                }
            }),
        ]);
    }

    public function update(Request $request, Bucket $bucket, Recording $comment)
    {
        $this->authorize('update', $comment);

        $comment->update([
            'recordable' => tap($this->newComment($request))->save(),
        ]);

        if ($request->wantsTurboStream()) {
            return response()->turboStream($comment);
        }

        return redirect($comment->recordableShowPath())
            ->withFragment($comment->pageFragmentId())
            ->with(['status' => __('Comment was updated!')]);
    }

    public function destroy(Bucket $bucket, Recording $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        return redirect($comment->parent->recordableShowPath())->with([
            'status' => __('Comment was deleted!'),
        ]);
    }

    private function newComment(Request $request, bool $required = true): Comment
    {
        return new Comment($request->validate([
            'content' => [$required ? 'required' : 'sometimes'],
        ]));
    }
}

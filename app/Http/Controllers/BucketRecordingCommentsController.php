<?php

namespace App\Http\Controllers;

use App\Models\Bucket;
use App\Models\Comment;
use App\Models\Recording;
use Illuminate\Http\Request;

class BucketRecordingCommentsController extends Controller
{
    public function index(Bucket $bucket, Recording $recording)
    {
        $this->authorize('view', $recording);

        return view('recording_comments.index', [
            'bucket' => $bucket,
            'recording' => $recording,
            'comments' => $recording->children()
                ->comments()
                ->oldest('id')
                ->cursorPaginate(15)
        ]);
    }

    public function create(Request $request, Bucket $bucket, Recording $recording)
    {
        $this->authorize('addComment', $recording);

        return view('recording_comments.create', [
            'bucket' => $bucket,
            'recording' => tap($recording->bucket->recordings()->make(), function ($commentRecording) use ($request, $recording) {
                $commentRecording->setRelation('recordable', $this->newComment($request, required: false));
                $commentRecording->setRelation('parent', $recording);
            }),
        ]);
    }

    public function store(Request $request, Bucket $bucket, Recording $recording)
    {
        $this->authorize('addComment', $recording);

        $commendRecording = $recording->bucket->record(
            $this->newComment($request),
            parent: $recording,
        );

        return redirect($commendRecording->recordableShowPath())
            ->withFragment((string) str($commendRecording->pageFragmentId())->after('#'))
            ->with('status', 'Comment was created!');
    }

    private function newComment(Request $request, bool $required = true): Comment
    {
        return new Comment($request->validate([
            'content' => [$required ? 'required' : 'sometimes'],
        ]));
    }
}

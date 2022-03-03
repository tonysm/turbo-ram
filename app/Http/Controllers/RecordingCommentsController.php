<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recording;
use Illuminate\Http\Request;

class RecordingCommentsController extends Controller
{
    public function index(Recording $recording)
    {
        $this->authorize('view', $recording);

        return view('recording_comments.index', [
            'recording' => $recording,
            'comments' => $recording->children()
                ->comments()
                ->oldest('id')
                ->cursorPaginate(15)
        ]);
    }

    public function create(Request $request, Recording $recording)
    {
        $this->authorize('addComment', $recording);

        return view('recording_comments.create', [
            'recording' => tap($recording->bucket->recordings()->make(), function ($commentRecording) use ($request, $recording) {
                $commentRecording->setRelation('recordable', $this->newComment($request, required: false));
                $commentRecording->setRelation('parentRecording', $recording);
            }),
        ]);
    }

    public function store(Request $request, Recording $recording)
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

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

    public function store(Request $request, Recording $recording)
    {
        $this->authorize('addComment', $recording);

        $commendRecording = $recording->bucket->record(
            $this->newComment($request),
            parent: $recording,
        );

        return redirect($recording->recordableShowPath([$commendRecording->pageFragmentId()]))
            ->with('status', 'Comment was created!');
    }

    private function newComment(Request $request): Comment
    {
        return new Comment($request->validate([
            'content' => ['required'],
        ]));
    }
}

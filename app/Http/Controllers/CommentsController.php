<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recording;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function show(Recording $recording)
    {
        return view('comments.show', [
            'recording' => $recording,
        ]);
    }

    public function edit(Request $request, Recording $recording)
    {
        $this->authorize('update', $recording);

        return view('comments.edit', [
            'recording' => tap($recording, function ($commentRecording) use ($request, $recording) {
                if ($request->old('content')) {
                    $commentRecording->setRelation('recordable', $this->newComment($request, required: false));
                }
            }),
        ]);
    }

    public function update(Request $request, Recording $recording)
    {
        $this->authorize('update', $recording);

        $recording->update([
            'recordable' => tap($this->newComment($request))->save(),
        ]);

        return redirect($recording->recordableShowPath())
            ->withFragment($recording->pageFragmentId())
            ->with(['status' => __('Comment was updated!')]);
    }

    public function destroy(Recording $recording)
    {
        $this->authorize('destroy', $recording);

        $recording->delete();

        return redirect($recording->parent->recordableShowPath())->with([
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

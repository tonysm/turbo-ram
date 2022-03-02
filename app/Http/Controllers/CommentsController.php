<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recording;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function update(Request $request, Recording $recording)
    {
        $this->authorize('update', $recording);

        $recording->update([
            'recordable' => tap($this->newComment($request))->save(),
        ]);

        return redirect($recording->recordableShowPath())->with([
            'status' => __('Comment was updated!'),
        ]);
    }

    private function newComment(Request $request): Comment
    {
        return new Comment($request->validate([
            'content' => ['required'],
        ]));
    }
}

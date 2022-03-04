<div class="flex flex-col border-t border-gray-100">
    <div id="@domid($recording, 'comments')" class="divide-y divide-gray-100">
        @each('comments._comment', $comments, 'recording')
    </div>

    @include('recordings._comment_form_trigger', ['recording' => $recording])
</div>

<div class="flex flex-col border-t border-gray-100">
    <div class="divide-y divide-gray-100">
        @each('recordings._comment', $comments, 'recording')
    </div>

    @include('recordings._comment_form_trigger', ['recording' => $recording])
</div>

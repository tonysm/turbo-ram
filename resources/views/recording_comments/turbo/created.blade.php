<x-turbo-stream :target="[$recording->parent, 'comments']" action="append">
    @include('comments._comment', [
        'recording' => $recording,
    ])
</x-turbo-stream>

<x-turbo-stream :target="[$recording->parent, 'create_comment']" action="replace">
    @include('recordings._comment_form_trigger', ['recording' => $recording->parent])
</x-turbo-stream>

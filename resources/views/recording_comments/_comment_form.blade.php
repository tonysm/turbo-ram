<x-jet-validation-errors class="mb-4" />

@if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ $recording->exists ? route('comments.update', $recording) : route('recordings.comments.store', $recording->parentRecording) }}">
    @csrf

    @if ($recording->exists)
        @method('PUT')
    @endif

    <div class="mt-4">
        <x-jet-label for="content" value="{{ __('Content') }}" />
        <x-trix id="content" class="block mt-1 w-full" name="content" autofocus autocomplete="off" :value="old('content', $recording->recordable->content->toTrixHtml())" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ $recording->exists ? route('comments.show', $recording) : route('buckets.posts.show', [$recording->parentRecording->bucket, $recording->parentRecording]) }}">
            {{ __('Cancel') }}
        </a>

        <x-jet-button class="ml-4">
            {{ __('Save') }}
        </x-jet-button>
    </div>
</form>

<x-jet-validation-errors class="mb-4" />

@if (session('status'))
    <div class="mb-4 text-sm font-medium text-green-600">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ $recording->exists ? route('buckets.comments.update', [$recording->bucket, $recording]) : route('buckets.recordings.comments.store', [$recording->bucket, $recording->parent]) }}" class="w-full">
    @csrf

    @if ($recording->exists)
        @method('PUT')
    @endif

    <div>
        <x-jet-label for="content" value="{{ __('Content') }}" />
        <x-trix id="content" class="block w-full mt-1" name="content" autofocus autocomplete="off" :value="old('content', $recording->recordable->content->toTrixHtml())" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ $recording->recordableShowPath() }}">
            {{ __('Cancel') }}
        </a>

        <x-jet-button class="ml-4">
            {{ __('Save') }}
        </x-jet-button>
    </div>
</form>

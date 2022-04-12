<x-app-layout>
    <x-slot name="title">{{ __('Create Comment') }}</x-slot>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <a href="{{ route('buckets.posts.show', [$recording->parent->bucket, $recording->parent]) }}">&larr; Back to post #{{ $recording->parent->id }}</a>
        </h2>
    </x-slot>

    <div class="sm:py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white sm:shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    <x-turbo-frame :id="[$recording->parent, 'create_comment']" action="replace" target="_top" class="block w-full">
                        @include('recording_comments._comment_form', [
                            'recording' => $recording,
                        ])
                    </x-turbo-frame>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

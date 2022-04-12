<x-app-layout>
    <x-slot name="title">{{ $recording->recordable->title }}</x-slot>

    <x-slot name="meta">
        <meta name="current-recording-id" content="{{ $recording->id }}" />
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                <a href="{{ route('buckets.blogs.posts.index', [$recording->bucket, $recording->parent]) }}">&larr; Back to posts</a>
            </h2>

            <div>
                <a class="hidden sm:inline" href="{{ route('buckets.posts.edit', [$recording->bucket, $recording]) }}" data-turbo-frame="@domid($recording)">Edit</a>
                <a class="sm:hidden" href="{{ route('buckets.posts.edit', [$recording->bucket, $recording]) }}">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="sm:py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white sm:shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    @include('recordings._recording', ['recording' => $recording])

                    @include('recordings._comments', ['recording' => $recording, 'comments' => $comments])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

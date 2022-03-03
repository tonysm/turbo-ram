<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('buckets.posts.index', $recording->bucket) }}">&larr; Back to posts</a>
            </h2>

            <div>
                <a href="{{ route('buckets.posts.edit', [$recording->bucket, $recording]) }}">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    @include('recordings._recording', ['recording' => $recording])

                    @include('recordings._comments', ['recording' => $recording, 'comments' => $comments])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

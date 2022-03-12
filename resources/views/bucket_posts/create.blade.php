<x-app-layout>
    <x-slot name="title">{{ __('Create Post') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ $recording->exists ? route('buckets.blogs.show', [$recording->bucket, $blog]) : route('dashboard') }}">&larr; Back to blog</a>
        </h2>
    </x-slot>

    <div class="sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    @include('bucket_posts._post_form', [
                        'bucket' => $bucket,
                        'recording' => $recording,
                    ])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

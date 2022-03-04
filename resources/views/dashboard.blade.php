<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    <p class="text-center">You currently have no posts.</p>
                    <p class="mt-4 text-center">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('buckets.blogs.posts.create', $bucket) }}">
                            {{ __('New Post') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

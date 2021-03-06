<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ $recording->recordableShowPath() }}">&larr; Back to post #{{ $recording->id }}</a>
        </h2>
    </x-slot>

    <div class="sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    @include('recordings._comment', ['recording' => $recording])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

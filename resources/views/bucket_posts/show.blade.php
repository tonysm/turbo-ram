<x-app-layout>
    <x-slot name="header">
        <x-recording-breadcrumbs :recording="$recording" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @include('recordings._recording', ['recording' => $recording])
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comments') }}
        </h2>
    </x-slot>

    <div class="sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:shadow-xl sm:rounded-lg">
                @each('recordings._recording', $comments, 'recording')

                {{ $comments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

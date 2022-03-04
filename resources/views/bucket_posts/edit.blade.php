<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editing Post #{{ $recording->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    <x-turbo-frame :id="$recording" target="_top" class="block w-full">
                        @include('bucket_posts._post_form', [
                            'bucket' => $bucket,
                            'recording' => $recording,
                        ])
                    </x-turbo-frame>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

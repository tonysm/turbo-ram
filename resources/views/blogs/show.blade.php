<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Posts') }}
            </h2>

            <div class="hidden sm:block">
                <a href="{{ route('buckets.blogs.posts.create', [$bucket, $blog]) }}">New Post</a>
            </div>
        </div>
    </x-slot>

    <div class="sm:py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    <div class="flex flex-col divide-y">
                        @each('bucket_posts._post_card', $posts, 'recording')
                    </div>

                    @if ($posts->hasPages())
                        <div class="mt-10">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

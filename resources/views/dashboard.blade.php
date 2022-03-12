<x-app-layout>
    <x-slot name="title">{{ __('Dashboard') }}</x-slot>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="sm:py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-8 sm:px-20">
                    @if ($bucket->events()->count() == 0)
                    <p class="text-center">You currently have no posts.</p>

                    <p class="mt-4 text-center">
                        <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('buckets.blogs.posts.create', [$bucket, $blog]) }}">
                            {{ __('New Post') }}
                        </a>
                    </p>
                    @else
                        <ul>
                            @foreach ($bucket->events as $event)
                                <li><a href="{{ $event->recording->recordableShowPath() }}">Event: {{ $event->event_type }} | Creator: {{ $event->creator?->name ?: 'Unknown' }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

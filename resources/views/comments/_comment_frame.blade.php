<div class="sm:w-5/6 flex items-start gap-2 text-lg">
    <x-user-avatar :user="$recording->creator" />

    <x-turbo-frame :id="[$recording, 'frame']" class="block w-full">
        <div class="font-semibold">{{ $recording->creator->name }}</div>

        <div>{!! $recording->recordable->content !!}</div>

        <div class="mt-2 transition transform sm:opacity-0 sm:group-hover:opacity-100">
            <a class="hidden sm:block underline" href="{{ route('buckets.comments.edit', [$recording->bucket, $recording]) }}" data-turbo-frame="@domid($recording, 'frame')">Edit</a>
            <a class="sm:hidden underline" href="{{ route('buckets.comments.edit', [$recording->bucket, $recording]) }}" data-turbo-frame="_top">Edit</a>
        </div>
    </x-turbo-frame>
</div>

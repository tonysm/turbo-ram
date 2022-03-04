<x-turbo-frame :id="[$recording, 'frame']" class="block w-full">
    <div>{!! $recording->recordable->content !!}</div>

    <div class="mt-2 transition transform opacity-0 group-hover:opacity-100">
        <a href="{{ route('buckets.comments.edit', [$recording->bucket, $recording]) }}" data-turbo-frame="@domid($recording, 'frame')">Edit</a>
    </div>
</x-turbo-frame>

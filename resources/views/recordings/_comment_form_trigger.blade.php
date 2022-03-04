<x-turbo-frame :id="[$recording, 'create_comment']" class="block w-full border-t border-gray-100 py-8">
    <a href="{{ route('buckets.recordings.comments.create', [$recording->bucket, $recording]) }}" class="my-4 block underline text-xl font-semibold leading-tight text-gray-400 hover:text-gray-600 text-center underline-offset-2">
        New Comment
    </a>
</x-turbo-frame>

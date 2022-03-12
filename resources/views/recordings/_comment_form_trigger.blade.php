<div class="flex border-t border-gray-100 py-8" id="@domid($recording, 'create_comment_trigger')">
    <div class="hidden sm:block w-1/6">&nbsp;</div>

    <div class="sm:w-5/6 flex items-start gap-2 text-lg">
        <x-user-avatar :user="Auth::user()" />

        <div class="w-full">
            <x-turbo-frame :id="[$recording, 'create_comment']" class="hidden sm:block w-full">
                <a href="{{ route('buckets.recordings.comments.create', [$recording->bucket, $recording]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    <span>New Comment</span>
                </a>
            </x-turbo-frame>

            <a href="{{ route('buckets.recordings.comments.create', [$recording->bucket, $recording]) }}" class="inline-flex sm:hidden items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                <span>New Comment</span>
            </a>
        </div>
    </div>
</div>

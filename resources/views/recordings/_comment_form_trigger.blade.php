<div class="hidden py-8 border-t border-gray-100 sm:flex" id="@domid($recording, 'create_comment_trigger')">
    <div class="hidden w-1/6 sm:block">&nbsp;</div>

    <div class="flex items-start gap-2 text-lg sm:w-5/6">
        <x-user-avatar :user="Auth::user()" />

        <div class="w-full">
            <x-turbo-frame :id="[$recording, 'create_comment']" class="hidden w-full sm:block">
                <a href="{{ route('buckets.recordings.comments.create', [$recording->bucket, $recording]) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25">
                    <span>New Comment</span>
                </a>
            </x-turbo-frame>

            <a href="{{ route('buckets.recordings.comments.create', [$recording->bucket, $recording]) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md sm:hidden hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25">
                <span>New Comment</span>
            </a>
        </div>
    </div>
</div>

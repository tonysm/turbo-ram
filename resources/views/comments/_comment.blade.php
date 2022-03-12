<div id="@domid($recording)" class="py-8 group">
    <div class="flex">
        <div class="hidden sm:flex w-1/6 flex-col items-center justify-start gap-2">
            <relative-time datetime="{{ $recording->created_at->toIso8601String() }}">
                {{ $recording->created_at->toFormattedDateString() }}
            </relative-time>

            @unless ($recording->created_at->eq($recording->updated_at))
            <span class="text-sm text-gray-400">(edited)</span>
            @endif
        </div>

        @include('comments._comment_frame', ['recording' => $recording])
    </div>
</div>

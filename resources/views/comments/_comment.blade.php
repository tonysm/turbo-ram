<div id="@domid($recording)" class="py-8 group">
    <div class="flex space-x-8">
        <div class="w-1/6 flex flex-col items-center gap-2">
            <span>
                {{ $recording->creator->name }}
            </span>

            <div class="flex items-center justify-center space-x-2">
                <relative-time datetime="{{ $recording->created_at->toIso8601String() }}">
                    {{ $recording->created_at->toFormattedDateString() }}
                </relative-time>

                @unless ($recording->created_at->eq($recording->updated_at))
                <span class="text-sm text-gray-400">(edited)</span>
                @endif
            </div>
        </div>

        <div class="w-5/6 text-lg">
            @include('comments._comment_frame', ['recording' => $recording])
        </div>
    </div>
</div>

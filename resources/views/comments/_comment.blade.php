<div id="@domid($recording)" class="py-8 group">
    <div class="flex space-x-8">
        <div class="w-1/6">
            {{ $recording->creator->name }}
        </div>

        <div class="w-5/6 text-lg">
            @include('comments._comment_frame', ['recording' => $recording])
        </div>
    </div>
</div>

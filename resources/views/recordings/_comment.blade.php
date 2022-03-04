<div class="py-8 group" id="@domid($recording)">
    <div class="flex space-x-8">
        <div class="w-1/6">
            {{ $recording->creator->name }}
        </div>

        <div class="w-5/6 text-lg">
            {!! $recording->recordable->content !!}
        </div>
    </div>

    <div class="mt-2 flex space-x-8">
        <div class="w-1/6">&nbsp;</div>
        <div class="w-5/6 opacity-0 transition transform group-hover:opacity-100">
            <a href="{{ route('buckets.comments.edit', [$recording->bucket, $recording]) }}">Edit</a>
        </div>
    </div>
</div>

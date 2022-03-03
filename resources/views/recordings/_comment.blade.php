<div class="flex space-x-8 py-8">
    <div>
        {{ $recording->creator->name }}
    </div>

    <div class="text-lg">
        {!! $recording->recordable->content !!}
    </div>
</div>

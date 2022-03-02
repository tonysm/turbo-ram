<div class="flex flex-col gap-2">
    <h1 class="font-semibold text-4xl text-gray-900 leading-tight">
        {{ $recording->recordable->title }}
    </h1>

    <div>
        {{ $recording->recordable->content }}
    </div>
</div>

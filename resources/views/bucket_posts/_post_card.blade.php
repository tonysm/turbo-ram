<a href="{{ $recording->recordableShowPath() }}" class="block">
    <div class="flex flex-col gap-2">
        <h1 class="font-semibold text-3xl text-gray-900 leading-tight">
            {{ $recording->recordable->title }}
        </h1>

        <div>
            {{ str($recording->recordable->content->toPlainText())->limit(300) }}
        </div>
    </div>
</a>

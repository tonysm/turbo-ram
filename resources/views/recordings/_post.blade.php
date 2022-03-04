<x-turbo-frame :id="$recording" class="flex flex-col gap-8 py-8">
    <h1 class="font-semibold text-4xl text-gray-900 leading-tight text-center">
        {{ $recording->recordable->title }}
    </h1>

    <div>
        {!! $recording->recordable->content !!}
    </div>
</x-turbo-frame>

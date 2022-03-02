<div class="flex flex-col gap-2">
    <h1 class="font-semibold text-4xl text-gray-900 leading-tight">
        {{ $post->title }}
    </h1>

    <div>
        {{ str($post->content->toPlainText())->limit(150) }}
    </div>
</div>

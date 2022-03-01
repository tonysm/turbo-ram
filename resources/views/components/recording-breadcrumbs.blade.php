<h2 class="flex space-x-2 font-semibold text-xl text-gray-800 leading-tight">
    @foreach ($parentsList as $parent)
        <a href="{{ $parent->breadcrumbsShowPath() }}">{{ $parent->breadcrumbsName() }}</a>
    @endforeach
</h2>

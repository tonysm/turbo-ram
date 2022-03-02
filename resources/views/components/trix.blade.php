@props(['disabled' => false, 'value' => '', 'id', 'name'])

<input type="hidden" {{ $disabled ? 'disabled' : '' }} id="{{ $id }}_input" value="{{ $value }}" name="{{ $name }}" />

<trix-editor
    id="{{ $id }}"
    input="{{ $id }}_input"
    {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}
></trix-editor>

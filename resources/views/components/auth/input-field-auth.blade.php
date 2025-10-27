@props([
    'name',
    'text',
    'type',
    'value' => null,
])

<div class="field-auth">
    <label class="label-field-auth" for="{{ $name }}">{{ $text }}</label>
    <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" class="input-field-auth">
</div>

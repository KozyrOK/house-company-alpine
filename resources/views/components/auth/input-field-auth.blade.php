@props([
    'name',
    'label' => null,
    'text' => null,
    'type' => 'text',
    'value' => null,
])

@php
    $caption = $label ? __($label) : $text;
@endphp

<div class="field-auth">
    <label
        class="label-field-auth"
        for="{{ $name }}"
    >
        {{ $caption }}
    </label>

    <input
        {{ $attributes->merge(['class' => 'input-field-auth']) }}
        id="{{ $name }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
    >
</div>

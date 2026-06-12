@props([
    'href' => null,
    'type' => 'button',
    'class' => '',
    'text' => null,
])

@php
    $caption = $text ? __($text) : $slot;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        {{ $caption }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>
        {{ $caption }}
    </button>
@endif

@props([
    'text',
    'type',
    'class',
])

<button type="{{ $type }}" class="{{ $class }}">
    {{ $text }}
</button>

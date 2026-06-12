@props([
    'text',
    'class' => '',
])

<a {{ $attributes->merge(['class' => $class]) }}>
    {{ __($text) }}
</a>

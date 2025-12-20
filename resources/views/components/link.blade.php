@php
    use Illuminate\Support\Str;

    $key = 'app.buttons.' . Str::slug($text, '_');
    $translated = __($key);

    if ($translated === $key) {
        $translated = $text;
    }
@endphp

@props([
    'text',
    'class' => '',
])

<a {{ $attributes->merge(['class' => $class]) }}>
    {{ $translated }}
</a>

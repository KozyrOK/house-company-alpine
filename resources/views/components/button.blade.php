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
    'href' => null,
    'type' => 'button',
    'class' => '',
])

@if($href)
    <a href="{{ $href }}" class="{{ $class }}">
        {{ $translated }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $class }}">
        {{ $translated }}
    </button>
@endif

@php
    use Illuminate\Support\Str;

    $key = "app.buttons." . Str::slug($text, '_');
    $translated_text = __($key);

    if ($translated_text === $key) {
        $translated_text = $text;
    }
@endphp

@props([
    'text',
    'href' => null,
    'type' => 'button',
    'class' => '',
])

@if($href && Str::startsWith($href, ':'))

    @php
        $expression = substr($href, 1);
    @endphp

    <a x-bind:href="{{ $expression }}" class="{{ $class }}">
        {{ $translated_text }}
    </a>

@elseif($href)
    <a href="{{ $href }}" class="{{ $class }}">
        {{ $translated_text }}
    </a>

@else
    <button type="{{ $type }}" class="{{ $class }}">
        {{ $translated_text }}
    </button>
@endif

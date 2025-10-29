@php
    use Illuminate\Support\Str;
@endphp

@props([
    'text',
    'type' => 'button',
    'class' => '',
])

@php
    $key = "app.buttons." . Str::slug($text, '_');
    $translated_text = __($key);
    if ($translated_text === $key) {
        $translated_text = $text;
    }
@endphp

<button type="{{ $type }}" class="{{ $class }}">
    {{ $translated_text }}
</button>

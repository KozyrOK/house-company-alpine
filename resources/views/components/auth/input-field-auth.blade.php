@php
    use Illuminate\Support\Str;
@endphp

@props([
    'name',
    'text',
    'type' => 'text',
    'value' => null,
])

@php
    $key = "app.inputs." . Str::slug($text, '_');
    $translated = __($key);
    if ($translated === $key) {
        $translated = $text;
    }
@endphp

<div class="field-auth">
    <label class="label-field-auth" for="{{ $name }}">{{ $text }}</label>
    <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" class="input-field-auth">
</div>

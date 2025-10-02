@props(['title'])

<div class="max-w-md mx-auto bg-white shadow p-6 rounded">
    @if($title)
        <h2 class="text-xl font-semibold mb-4">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>

@php
    $avatar = asset('images/default_avatar.webp');

    if (auth()->check() && auth()->user()->avatar_path) {
        $avatar = asset('storage/' . auth()->user()->avatar_path);
    }
@endphp

<img src="{{ $avatar }}" alt="avatar" class="header-avatar-image">

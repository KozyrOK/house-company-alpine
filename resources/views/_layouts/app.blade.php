<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        const darkMode = localStorage.getItem('darkMode') === 'true';
        if (darkMode) document.documentElement.classList.add('dark');
        window.__darkMode = darkMode;
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.layouts.hc'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<x-layouts::header/>

<div class="navbar-header-wrapper">
    <x-header.company-image/>
    <x-layouts::navbar-header/>
</div>

<main class="main-wrapper">
    <div
        x-data
        x-init="$el.style.backgroundImage = `url(${Alpine.store('assets').mainBackground})`"
        class="content-background">
    </div>

    <div class="main-container">
        <div class="content-inner">
            @yield('content')
        </div>
    </div>
</main>

<x-layouts::footer/>

</body>
</html>

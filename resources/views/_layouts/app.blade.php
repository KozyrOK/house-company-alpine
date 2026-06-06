<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        const backendTheme = @json(session('theme', auth()->user()->theme ?? 'system'));
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const darkMode = backendTheme === 'dark' || (backendTheme === 'system' && prefersDark);
        if (darkMode) document.documentElement.classList.add('dark');
        window.__theme = backendTheme;
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
    <x-layouts::navbar-header/>
</div>

<main class="main-wrapper">
    <div
        x-data="defaultContentBackground"
        x-init="init"
        class="content-background">
    </div>

    <div class="main-container">
        <div class="content-inner">
            @yield('content')
        </div>
    </div>
</main>

<x-confirm-modal/>

<x-layouts::footer/>

</body>
</html>

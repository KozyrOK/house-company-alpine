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
    <title>@yield('title', 'Housing Company')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<x-header/>

<div class="navbar-header-wrapper">
    <x-company-image/>
    <x-navbar-header/>
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

<x-footer/>

</body>
</html>

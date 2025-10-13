<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data
      x-init="document.documentElement.classList.toggle('dark', JSON.parse(localStorage.getItem('darkMode') ?? 'false'))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Housing Company')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<x-main_component.header />

<x-main_component.header-button/>

<main class="page-container">

    @yield('content')
</main>

<x-main_component.footer />

</body>
</html>

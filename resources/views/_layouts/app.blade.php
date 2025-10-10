<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: JSON.parse(localStorage.getItem('darkMode') ?? 'false') }"
      x-init="document.documentElement.classList.toggle('dark', darkMode)">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Housing Company')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-[#b5d8ce] dark:bg-gray-900 text-gray-900 dark:text-gray-100">

<header class="relative w-full">
    <div
        x-data="{
                darkMode: JSON.parse(localStorage.getItem('darkMode') ?? 'false'),
                light: Alpine.store('assets').headerPattern,
                dark: Alpine.store('assets').headerPatternDark
            }"
        class="h-48 bg-repeat bg-top transition-all duration-500"
        :style="{
                backgroundImage: darkMode
                    ? `url(${dark})`
                    : `url(${light})`
            }"
    ></div>

    <div class="absolute top-3 right-6 flex items-center space-x-4">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white font-medium hover:text-[#198666]">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-white font-medium hover:text-[#198666]">Login</a>
        @endauth

    </div>

    <nav class="bg-[#0f616d] text-white font-semibold">
        <ul class="hidden md:flex justify-center space-x-8 py-3">
            @auth
                <li><a href="{{ route('companies.index') }}" class="hover:text-[#198666]">Main</a></li>
                @if(Auth::user()->hasRole(['admin','superadmin']))
                    <li><a href="{{ route('admin') }}" class="hover:text-[#198666]">Admin</a></li>
                @endif
                <li><a href="{{ route('dashboard') }}" class="hover:text-[#198666]">Dashboard</a></li>
                <li><a href="{{ route('chat') }}" class="hover:text-[#198666]">Chat</a></li>
                <li><a href="{{ route('forum') }}" class="hover:text-[#198666]">Forum</a></li>
                <li><a href="{{ route('info') }}" class="hover:text-[#198666]">Info</a></li>
            @else
                <li><a href="{{ route('info') }}" class="hover:text-[#198666]">About Project</a></li>
            @endauth
        </ul>
    </nav>
</header>

<main class="flex-1 py-6 px-4 md:px-10 bg-[#a2ae9c] dark:bg-gray-800 rounded-2xl shadow-inner max-w-7xl mx-auto mt-6">
    @yield('content')
</main>

<footer class="bg-[#0f616d] text-white text-center py-4 mt-6">
    <p class="text-sm">&copy; {{ date('Y') }} Housing Company</p>
</footer>

</body>
</html>

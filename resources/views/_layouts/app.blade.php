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

<header class="header-container relative w-full">

    <div
        x-data="{
                darkMode: JSON.parse(localStorage.getItem('darkMode') ?? 'false'),
                light: Alpine.store('assets').headerPattern,
                dark: Alpine.store('assets').headerPatternDark
            }"
        class="h-28 bg-repeat-x bg-top transition-all duration-500"
        :style="{
                backgroundImage: darkMode
                    ? `url(${dark})`
                    : `url(${light})`
            }"
    ></div>

    <div class="absolute top-3 right-6">
        <div class="bg-blue-600 rounded-lg p-2 flex items-center space-x-4">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white font-medium hover:text-red-500">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-white font-medium hover:text-red-500">Login</a>
            @endauth

            {{--        <x-locale-switch />--}}
            {{--        <x-theme-toggle />    --}}
        </div>



    </div>

    <nav class="bg-[#0f616d] text-white font-semibold">
        <ul class="flex justify-end space-x-8 py-3 px-4">
            @auth
                <li><a href="{{ route('companies.index') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('companies.index') ? 'bg-[#198666] text-white' : '' }}">Main</a></li>
                @if(Auth::user()->isSuperAdminForHeader())
                    <li><a href="{{ route('header.superadmin') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('header.superadmin') ? 'bg-[#198666] text-white' : '' }}">Admin</a></li>
                @endif
                <li><a href="{{ route('dashboard') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-[#198666] text-white' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('chat') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('chat') ? 'bg-[#198666] text-white' : '' }}">Chat</a></li>
                <li><a href="{{ route('forum') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('forum') ? 'bg-[#198666] text-white' : '' }}">Forum</a></li>
                <li><a href="{{ route('info') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('info') ? 'bg-[#198666] text-white' : '' }}">Info</a></li>
            @else
                <li><a href="{{ route('info') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('info') ? 'bg-[#198666] text-white' : '' }}">About Project</a></li>
            @endauth
        </ul>
    </nav>
</header>

<main class="flex-1 py-6 px-4 md:px-10 bg-[#a2ae9c] dark:bg-gray-800 rounded-2xl shadow-inner max-w-7xl mx-auto mt-6"
      x-data="{ backgroundImage: Alpine.store('assets').mainBackground }"
      :style="backgroundImage ? `background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(${backgroundImage}); background-size: cover; background-position: center;` : ''">
    <div class="text-white">
        @yield('content')
    </div>
</main>

<footer class="bg-[#0f616d] text-white text-center py-4 mt-6">
    <p class="text-sm">&copy; {{ date('Y') }} Housing Company</p>
</footer>

</body>
</html>

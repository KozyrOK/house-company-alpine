<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="theme()"
      x-init="init()"
      :class="{ 'dark': dark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'HousingCompany'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">

{{-- HEADER --}}
<header class="relative">
    {{-- Background image / pattern --}}
    <div class="h-48 sm:h-64 md:h-80 bg-[url('/images/header-pattern.jpg')] bg-repeat bg-cover"></div>

    {{-- Top-right controls: login/logout, locale, dark mode --}}
    <div class="absolute top-4 right-4 flex items-center space-x-3">

        {{-- Locale switch (temporary placeholder) --}}
        <div class="hidden sm:flex items-center space-x-1">
            <a href="?lang=en" class="text-sm">EN</a>
            <span class="text-sm">/</span>
            <a href="?lang=uk" class="text-sm">UK</a>
        </div>

        {{-- Dark mode toggle --}}
        <button @click="toggle()" class="p-2 rounded-md border" title="Toggle theme">
            <span x-text="dark ? '🌙' : '☀️'"></span>
        </button>

        {{-- Auth buttons --}}
        @guest
            <a href="{{ route('login') }}" class="px-3 py-2 rounded bg-indigo-600 text-white text-sm">Login</a>
        @else
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-3 py-2 rounded bg-red-500 text-white text-sm">Logout</button>
            </form>
        @endguest
    </div>

    {{-- Navigation menu below the header image --}}
    <nav class="bg-white dark:bg-gray-800 border-t shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-12">
                <div class="flex items-center space-x-4">

                    {{-- Always visible links --}}
                    <a href="{{ route('info') }}" class="text-sm font-medium">Info</a>

                    {{-- Authenticated user links --}}
                    @auth
                        <a href="{{ route('companies.index') }}" class="text-sm font-medium">Main</a>
                        <a href="{{ route('forum') }}" class="text-sm font-medium">Forum</a>
                        <a href="{{ route('chat') }}" class="text-sm font-medium">Chat</a>

                        {{-- Admin link based on user role --}}
                        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-red-500">
                                Admin
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="text-xs text-gray-500">Housing Company</div>
            </div>
        </div>
    </nav>
</header>

{{--MAIN CONTENT--}}
<main class="max-w-7xl mx-auto px-4 py-8">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="border-t py-6 text-center text-sm text-gray-500">
    © {{ date('Y') }} Housing Company
</footer>

{{-- DARK MODE SCRIPT --}}
<script>
    function theme() {
        return {
            dark: localStorage.getItem('hc_dark') === '1',
            init() { this.dark = localStorage.getItem('hc_dark') === '1' },
            toggle() {
                this.dark = !this.dark;
                localStorage.setItem('hc_dark', this.dark ? '1' : '0');
            }
        }
    }
</script>

</body>
</html>

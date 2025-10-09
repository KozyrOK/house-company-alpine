<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    menuOpen: false,
    toggleDark() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        document.documentElement.classList.toggle('dark', this.darkMode);
    }
}"
      x-init="document.documentElement.classList.toggle('dark', darkMode)">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Housing Company')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#b5d8ce] dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">

<!-- Header -->
<header class="relative w-full">
    <!-- Background pattern (switches with theme) -->
    <div
        class="h-40 bg-cover bg-center transition-all duration-500"
        :style="darkMode
                ? 'background-image: url(@/images/header-pattern-dark.png)'
                : 'background-image: url(@/images/header-pattern.png)'">
    </div>

    <!-- Top controls -->
    <div class="absolute top-2 right-4 flex items-center space-x-4">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white font-medium hover:text-[#198666]">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-white font-medium hover:text-[#198666]">Login</a>
        @endauth

        <!-- Locale switch -->
        <button @click="$dispatch('locale-switch')" class="text-white hover:text-[#198666]">🌐</button>

        <!-- Dark mode switch -->
        <button @click="toggleDark()" class="text-white hover:text-[#198666]">
            <template x-if="darkMode">🌙</template>
            <template x-if="!darkMode">☀️</template>
        </button>

        <!-- Mobile menu button -->
        <button @click="menuOpen = !menuOpen" class="md:hidden text-white hover:text-[#198666]">
            ☰
        </button>
    </div>

    <!-- Navigation -->
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

        <!-- Mobile menu -->
        <ul
            x-show="menuOpen"
            @click.away="menuOpen = false"
            x-transition
            class="md:hidden bg-[#0f616d] flex flex-col items-center py-3 space-y-2">
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

<!-- Main Content -->
<main class="flex-1 py-6 px-4 md:px-10 bg-[#a2ae9c] dark:bg-gray-800 rounded-2xl shadow-inner max-w-7xl mx-auto mt-4 transition-all duration-500">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-[#0f616d] text-white text-center py-4 mt-6">
    <p class="text-sm">&copy; {{ date('Y') }} Housing Company Project</p>
</footer>

</body>
</html>

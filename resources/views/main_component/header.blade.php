<header class="header-wrapper">
    <div x-data="headerBackground" class="header-pattern"
         :style="{ backgroundImage: `url(${image})` }"></div>

    <div class="header-topbar">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white font-medium hover:text-[var(--color-accent-hover)]">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-white font-medium hover:text-[var(--color-accent-hover)]">Login</a>
        @endauth

        <x-locale-switch />
        <x-theme-toggle />
    </div>
</header>

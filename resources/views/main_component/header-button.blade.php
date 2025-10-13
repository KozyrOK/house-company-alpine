<nav class="navbar">
    <ul class="hidden md:flex justify-center space-x-8 py-3">
        @auth
            <li><a href="{{ route('companies.index') }}">Main</a></li>
            @if(Auth::user()->isSuperAdminForHeader())
                <li><a href="{{ route('main_component.superadmin') }}" class="hover:text-[#198666] px-4 py-2 rounded {{ request()->routeIs('main_component.superadmin') ? 'bg-[#198666] text-white' : '' }}">Admin</a></li>
            @endif
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('chat') }}">Chat</a></li>
            <li><a href="{{ route('forum') }}">Forum</a></li>
            <li><a href="{{ route('info') }}">Info</a></li>
        @else
            <li><a href="{{ route('info') }}">About Project</a></li>
        @endauth
    </ul>
</nav>

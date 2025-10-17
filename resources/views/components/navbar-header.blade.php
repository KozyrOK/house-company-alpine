<nav class="navbar-header">
    <ul>
        @auth
            <li><a href="{{ route('companies.index') }}" class="{{ request()->routeIs('companies.index') ? 'active' : '' }}">Main</a></li>

            @if(Auth::user()->isSuperAdminForHeader())
                <li><a href="{{ route('main-component.superadmin') }}" class="{{ request()->routeIs('main-component.superadmin') ? 'active' : '' }}">Admin</a></li>
            @endif

            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">Chat</a></li>
            <li><a href="{{ route('forum') }}" class="{{ request()->routeIs('forum') ? 'active' : '' }}">Forum</a></li>
            <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">Info</a></li>
        @else
            <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">Info</a></li>
        @endauth
    </ul>
</nav>


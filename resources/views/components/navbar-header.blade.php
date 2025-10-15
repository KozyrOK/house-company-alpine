<nav class="navbar-header">
    <ul class="hidden md:flex justify-end space-x-8 py-3">
        @auth
            <li><a href="{{ route('companies.index') }}">Main</a></li>
            @if(Auth::user()->isSuperAdminForHeader())
                <li><a href="{{ route('main-component.superadmin') }}">Admin</a></li>
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

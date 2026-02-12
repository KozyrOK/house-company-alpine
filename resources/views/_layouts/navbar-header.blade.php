<nav class="navbar-header">
    <ul>
        @auth

            @if(auth()->user()->canAccessAdminPanel())
                <li>
                    <a href="{{ route('admin.index') }}"
                       class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        {{ __('app.layouts.admin') }}
                    </a>
                </li>
            @endif

            @if(auth()->user()->hasMainAccess())
                <li>
                    <a href="{{ route('main.index') }}"
                       class="{{ request()->routeIs('main.*') ? 'active' : '' }}">
                        {{ __('app.layouts.main') }}
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    {{__('app.layouts.dashboard')}}
                </a>
            </li>
            <li>
                <a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">
                    {{__('app.layouts.chat')}}
                </a>
            </li>
            <li>
                <a href="{{ route('forum') }}" class="{{ request()->routeIs('forum') ? 'active' : '' }}">
                    {{__('app.layouts.forum')}}
                </a>
            </li>
            <li>
                <a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">
                    {{__('app.layouts.info')}}
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">
                    {{__('app.layouts.info')}}
                </a>
            </li>
        @endauth
    </ul>
</nav>

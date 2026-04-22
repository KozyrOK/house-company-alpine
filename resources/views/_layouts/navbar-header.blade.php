<nav class="navbar-header">
    <x-header.company-image/>
    <x-header.company-switcher/>
    <ul>
        @auth
            @php
                $user = auth()->user();
                $currentCompany = currentCompany();
                $role = $currentCompany ? $user->roleIn($currentCompany) : null;
            @endphp

            @if($user->isSuperAdmin())
                <li><a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('app.layouts.admin') }}</a></li>
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">{{__('app.layouts.dashboard')}}</a></li>
                <li><a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">{{__('app.layouts.chat')}}</a></li>
                <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">{{__('app.layouts.info')}}</a></li>
            @elseif($role === 'admin')
                <li><a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('app.layouts.admin') }}</a></li>
                <li><a href="{{ route('company.current') }}" class="{{ request()->routeIs('company.current') ? 'active' : '' }}">Company</a></li>
                <li><a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">{{__('app.layouts.chat')}}</a></li>
                <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">{{__('app.layouts.info')}}</a></li>
            @elseif(in_array($role, ['company_head', 'user'], true))
                <li><a href="{{ route('main.index') }}" class="{{ request()->routeIs('main.*') ? 'active' : '' }}">{{ __('app.layouts.main') }}</a></li>
                <li><a href="{{ route('company.current') }}" class="{{ request()->routeIs('company.current') ? 'active' : '' }}">Company</a></li>
                <li><a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">{{__('app.layouts.chat')}}</a></li>
                <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">{{__('app.layouts.info')}}</a></li>
            @endif
        @else
            <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">{{__('app.layouts.info')}}</a></li>
        @endauth
    </ul>
</nav>

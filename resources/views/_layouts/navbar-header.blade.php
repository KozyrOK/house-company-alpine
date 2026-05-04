<nav class="navbar-header">
    <x-header.company-image/>
    <ul>
        @auth
            @php
                $user = auth()->user();
                $currentCompany = currentCompany();
                $role = $currentCompany ? $user->roleIn($currentCompany) : null;
                $hasMultipleCompanies = !$user->isSuperAdmin() && $user->companies()->count() > 1;
            @endphp

            @if($hasMultipleCompanies)
                <li><x-header.company-switcher/></li>
            @endif

            @if($user->isSuperAdmin())
                <li><a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('app.layouts.admin') }}</a></li>
                <li><a href="{{ route('action-approve.index') }}" class="{{ request()->routeIs('action-approve.*') ? 'active' : '' }}">Action Approve</a></li>
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">{{__('app.layouts.dashboard')}}</a></li>
            @elseif($role === 'admin')
                <li><a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('app.layouts.admin') }}</a></li>
                <li><a href="{{ route('action-approve.index') }}" class="{{ request()->routeIs('action-approve.*') ? 'active' : '' }}">Action Approve</a></li>
                <li><a href="{{ route('company.current') }}" class="{{ request()->routeIs('company.current') ? 'active' : '' }}">Company</a></li>
            @elseif($role === 'company_head')
                <li><a href="{{ route('main.index') }}" class="{{ request()->routeIs('main.*') ? 'active' : '' }}">{{ __('app.layouts.main') }}</a></li>
                <li><a href="{{ route('action-approve.index') }}" class="{{ request()->routeIs('action-approve.*') ? 'active' : '' }}">Action Approve</a></li>
                <li><a href="{{ route('company.current') }}" class="{{ request()->routeIs('company.current') ? 'active' : '' }}">Company</a></li>
            @elseif($role === 'user')
                <li><a href="{{ route('main.index') }}" class="{{ request()->routeIs('main.*') ? 'active' : '' }}">{{ __('app.layouts.main') }}</a></li>
                <li><a href="{{ route('company.current') }}" class="{{ request()->routeIs('company.current') ? 'active' : '' }}">Company</a></li>
            @endif
            <li><a href="{{ route('chat') }}" class="{{ request()->routeIs('chat') ? 'active' : '' }}">{{__('app.layouts.chat')}}</a></li>
            <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">{{__('app.layouts.info')}}</a></li>
        @else
            <li><a href="{{ route('info') }}" class="{{ request()->routeIs('info') ? 'active' : '' }}">{{__('app.layouts.info')}}</a></li>
        @endauth
    </ul>
</nav>

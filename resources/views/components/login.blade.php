<div class="login-topbar">
    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="login-topbar-button">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="login-topbar-button inline-block">Login</a>
    @endauth
</div>

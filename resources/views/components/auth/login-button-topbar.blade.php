<section class="login-topbar">

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="login-topbar-button">__('app.components.logout')</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="login-topbar-button inline-block">__('app.components.login')</a>
    @endauth

</section>

<section class="window-wrapper-auth">

    <div class="close-button-auth">
        <a href="{{ url()->previous() }}">
            &times;
        </a>
    </div>

    <h1>Login</h1>

    <div title="Login">

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label class="label-field-auth" for="email">Email</label>
                <input id="email" type="email" name="email" required autofocus
                       class="input-field-auth">
            </div>

            <div class="field-auth">
                <label class="label-field-auth" for="password">Password</label>
                <input id="password" type="password" name="password" required
                       class="input-field-auth">
            </div>

            <button type="submit" class="button-submit-auth">
                Login
            </button>
        </form>

        <div class="link-text-wrapper-auth">
            @if (Route::has('password.request'))
                <a class="link-text-auth" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif

            @if (Route::has('register'))
                <a class="link-text-auth" href="{{ route('register') }}">
                    Register
                </a>
            @endif
        </div>
    </div>

</section>

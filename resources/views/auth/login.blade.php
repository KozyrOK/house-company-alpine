@extends('_layouts.app')

@section('title','Login')

@section('content')

    <section class="window-wrapper-auth">

        <x-auth.close-button-auth/>

        <h1>__('app.auth.login')</h1>

        <div title="Login">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <x-auth.input-field-auth
                    name="email"
                    text="Email"
                    type="email"
                    required autofocus
                />

                <x-auth.input-field-auth
                    name="password"
                    text="Password"
                    type="password"
                    required
                />

                <x-button
                    text="Login"
                    type="submit"
                    class="button-submit-auth"
                />

            </form>

            <div class="link-text-wrapper-auth">
                @if (Route::has('password.request'))
                    <a class="link-text-auth" href="{{ route('password.request') }}">
                        __('app.auth.forgot_password')
                    </a>
                @endif

                @if (Route::has('register'))
                        <a class="link-text-auth" href="{{ route('register') }}">
                            __('app.auth.register')
                        </a>
                    @endif
            </div>

    <section/>

@endsection

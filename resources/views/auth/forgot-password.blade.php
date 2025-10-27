@extends('_layouts.app')

@section('title','Forgot Password')

@section('content')

    <section class="window-wrapper-auth">

        <x-auth.close-button-auth/>

        <h1>Forgot Password?</h1>

        <div title="Forgot Password">
            <p>
                Enter your email and
                we will send you a password reset link.
            </p>

            @if (session('status'))
                <div>{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                 @csrf

                <x-auth.input-field-auth
                name="email"
                text="Email"
                type="email"
                required autofocus
                />

                <x-button
                text="Send Reset Link"
                type="submit"
                class="button-submit-auth"
                />

            </form>

        </div>

    </section>

@endsection

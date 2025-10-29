@extends('_layouts.app')

@section('title','Register')

@section('content')

    <section class="window-wrapper-auth">

        <x-auth.close-button-auth/>

        <h1>__('app.auth.register')</h1>

        <div title="Login">

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <x-auth.input-field-auth
                    name="first_name"
                    text="First name"
                    type="text"
                    required autofocus
                />

                <x-auth.input-field-auth
                    name="second_name"
                    text="Second name"
                    type="text"
                    required autofocus
                />

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

                <x-auth.input-field-auth
                    name="confirm_password"
                    text="Confirm Password"
                    type="password"
                    required
                />

                <x-auth.input-field-auth
                    name="phone"
                    text="Phone"
                    type="text"
                    required
                />

                <x-button
                    text="Register"
                    type="submit"
                    class="button-submit-auth"
                />

            </form>

        </div>

    </section>

@endsection

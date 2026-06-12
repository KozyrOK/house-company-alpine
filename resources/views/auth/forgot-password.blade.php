@extends('_layouts.app')

@section('title','Forgot Password')

@section('content')

    <section class="window-wrapper-auth">

        <x-auth.close-button-auth/>

        <h1>{{__('app.auth.forgot_password')}}</h1>

        <div title="Forgot Password">
            <p>
                {{__('app.auth.forgot_password_text2')}}
            </p>

            @if (session('status'))
                <div>{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                 @csrf

                <x-auth.input-field-auth
                name="email"
                label="app.inputs.email"
                type="email"
                required autofocus
                />

                <x-button
                text="app.auth.send_reset_link"
                type="buttons.submit"
                class="button-submit-auth"
                />

            </form>

        </div>

    </section>

@endsection

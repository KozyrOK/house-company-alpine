@extends('_layouts.app')

@section('title','Reset Password')

@section('content')

    <section class="window-wrapper-auth">

        <x-auth.close-button-auth/>

        <h1>{{__('app.auth.reset_password')}}</h1>

        <div title="Reset Password">

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-auth.input-field-auth
                name="email"
                label="app.auth.email"
                type="email"
                :value="$request->email"
                required
            />

            <x-auth.input-field-auth
                name="new_password"
                label="app.auth.new_password"
                type="password"
                required
            />

            <x-auth.input-field-auth
                name="confirm_new_password"
                label="app.auth.confirm_new_password"
                type="password"
                required
            />

            <x-button
                text="app.buttons.reset_password"
                type="submit"
                class="button-submit-auth"
            />

        </form>

    </div>

    </section>

@endsection

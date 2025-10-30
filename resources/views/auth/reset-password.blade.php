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
                label="Email"
                type="email"
                :value="$request->email"
                required
            />

            <x-auth.input-field-auth
                name="new_password"
                text="New Password"
                type="password"
                required
            />

            <x-auth.input-field-auth
                name="confirm_new_password"
                text="Confirm New Password"
                type="password"
                required
            />

            <x-button
                text="Reset Password"
                type="submit"
                class="button-submit-auth"
            />

        </form>

    </div>

    </section>

@endsection

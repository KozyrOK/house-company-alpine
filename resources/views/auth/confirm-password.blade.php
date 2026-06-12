@extends('_layouts.app')

@section('title','Confirm Password')

@section('content')

    <div title="{{__('app.auth.confirm_password')}}">

        <p>
            {{__('app.auth.confirm_password_text')}}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <x-auth.input-field-auth
                name="password"
                label="app.inputs.password"
                type="password"
                required
                autocomplete="current-password"
            />

            <x-button
                text="app.buttons.confirm"
                type="submit"
                class="button-submit-auth"
            />

        </form>

    </div>

@endsection

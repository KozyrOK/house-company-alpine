@extends('_layouts.app')

@section('title','Register')

@section('content')

    <section class="window-wrapper-auth">

        <x-auth.close-button-auth/>

        <h1>{{__('app.auth.register')}}</h1>

        <div title="Login">

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <x-auth.input-field-auth
                    name="first_name"
                    label="app.inputs.first_name"
                    type="text"
                    required autofocus
                />

                <x-auth.input-field-auth
                    name="second_name"
                    label="app.inputs.second_name"
                    type="text"
                    required autofocus
                />

                <x-auth.input-field-auth
                    name="email"
                    label="app.inputs.email"
                    type="email"
                    required autofocus
                />

                <x-auth.input-field-auth
                    name="password"
                    label="app.inputs.password"
                    type="password"
                    required
                />

                <x-auth.input-field-auth
                    name="password_confirmation"
                    label="app.inputs.confirm_password"
                    type="password"
                    required
                />

                <div class="wrapper-input-auth">
                    <label for="company_id" class="label-text-auth">{{__('app.inputs.company_optional')}}</label>
                    <select name="company_id" id="company_id" class="input-field">
                        <option value="">{{__('app.inputs.without_company')}}</option>
                        @foreach(($companies ?? collect()) as $company)
                            <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <x-auth.input-field-auth
                    name="phone"
                    label="app.inputs.phone"
                    type="text"
                    required
                />

                <x-button
                    text="app.buttons.register"
                    type="submit"
                    class="button-submit-auth"
                />

            </form>

        </div>

    </section>

@endsection

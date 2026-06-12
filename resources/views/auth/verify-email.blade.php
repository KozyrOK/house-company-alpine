@extends('_layouts.app')

@section('title','Verify Email')

@section('content')

    <section class="window-wrapper-auth">

    <div title="Verify Email">

        <p>
            {{__('app.auth.reset_verify_email_text1')}}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div>
                {{__('app.auth.reset_verify_email_text2')}}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-button
                text="app.auth.resend_verification_email"
                type="submit"
                class="button-submit-auth"
            />

        </form>

    </div>

    </section>

@endsection

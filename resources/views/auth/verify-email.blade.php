@extends('_layouts.app')

@section('title','Verify Email')

@section('content')

    <section class="window-wrapper-auth">

    <div title="Verify Email">

        <p>
            Thanks for signing up! Please check your email for a verification link.
            If you didn’t receive it, you can request another.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div>
                A new verification link has been sent to your email.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-button
                text="Resend Verification Email"
                type="submit"
                class="button-submit-auth"
            />

        </form>

    </div>

    </section>

@endsection

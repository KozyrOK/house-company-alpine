@extends('layouts.app')

@section('title','Verify Email')

@section('content')
    <x-auth-card title="Verify Email">
        <p class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Please check your email for a verification link.
            If you didn’t receive it, you can request another.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-green-600 text-sm">
                A new verification link has been sent to your email.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
                Resend Verification Email
            </button>
        </form>
    </x-auth-card>
@endsection

@extends('_layouts.app')

@section('title','Forgot Password')

@section('content')
    <x-auth-card title="Forgot Password">
        <p class="mb-4 text-sm text-gray-600">
            Enter your email and we will send you a password reset link.
        </p>

        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="block text-sm">Email</label>
                <input id="email" type="email" name="email" required autofocus
                       class="w-full border rounded p-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
                Send Reset Link
            </button>
        </form>
    </x-auth-card>
@endsection

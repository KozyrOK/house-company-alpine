@extends('layouts.app')

@section('title','Reset Password')

@section('content')
    <x-auth-card title="Reset Password">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="block text-sm">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label for="password" class="block text-sm">New Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="block text-sm">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full border rounded p-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
                Reset Password
            </button>
        </form>
    </x-auth-card>
@endsection

@extends('layouts.app')

@section('title','Register')

@section('content')
    <x-auth-card title="Register">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="block text-sm">Name</label>
                <input id="name" type="text" name="name" required autofocus
                       class="w-full border rounded p-2">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="block text-sm">Email</label>
                <input id="email" type="email" name="email" required
                       class="w-full border rounded p-2">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="block text-sm">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border rounded p-2">
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="block text-sm">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full border rounded p-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
                Register
            </button>
        </form>
    </x-auth-card>
@endsection

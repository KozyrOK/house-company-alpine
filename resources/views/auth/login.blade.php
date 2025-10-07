@extends('_layouts.app')

@section('title','Login')

@section('content')
    <x-auth-card title="Login">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="block text-sm">Email</label>
                <input id="email" type="email" name="email" required autofocus
                       class="w-full border rounded p-2">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="block text-sm">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border rounded p-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
                Login
            </button>
        </form>

        <div class="mt-4 flex justify-between text-sm">
            @if (Route::has('password.request'))
                <a class="text-blue-600 hover:underline" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif

            @if (Route::has('register'))
                <a class="text-blue-600 hover:underline" href="{{ route('register') }}">
                    Register
                </a>
            @endif
        </div>
    </x-auth-card>

{{--    @guest--}}
{{--        <div class="mt-6">--}}
{{--            <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Log in</a>--}}
{{--            @if (Route::has('register'))--}}
{{--                <a href="{{ route('register') }}" class="ml-3 px-4 py-2 border rounded">Register</a>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endguest--}}


@endsection

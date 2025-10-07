@extends('_layouts.app')

@section('title','Confirm Password')

@section('content')
    <x-auth-card title="Confirm Password">
        <p class="mb-4 text-sm text-gray-600">
            This is a secure area. Please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-3">
                <label for="password" class="block text-sm">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border rounded p-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
                Confirm
            </button>
        </form>
    </x-auth-card>
@endsection

@extends('_layouts.app')

@section('title', 'About Project')

@section('content')
    <section class="prose max-w-3xl mx-auto">
        <h1>About the Housing Company Project</h1>
        <p>
            This is a PET project for managing condominium associations.
            The project allows residents and administrators to manage posts,
            users, and company information in one place.
        </p>

        @guest
            <div class="mt-6">
                <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-3 px-4 py-2 border rounded">Register</a>
                @endif
            </div>
        @endguest
    </section>
@endsection

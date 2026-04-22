@extends('_layouts.app')

@section('title', 'User Detail')

@section('content')

    <section>

        <h1>User</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="← Back to list" href="{{ isset($company) ? route('main.users.index', $company) : route('dashboard') }}" class="button-list"/>
                </div>

                <div>
                    <img class="company-image" src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-image-company.webp') }}" alt="user image">
                </div>

                <div class="button-wrapper">
                    <x-link text="{{ isset($company) ? 'Main Menu' : 'Dashboard' }}"
                            href="{{ isset($company) ? route('main.index') : route('dashboard') }}"
                            class="button-list"/>
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $user->id }}</td></tr>
                <tr><th class="key-content-item">Name</th><td class="value-content-item">{{ $user->first_name }} {{ $user->second_name }}</td></tr>
                <tr><th class="key-content-item">Email</th><td class="value-content-item">{{ $user->email }}</td></tr>
                <tr><th class="key-content-item">Phone</th><td class="value-content-item">{{ $user->phone ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Status</th><td class="value-content-item">{{ $user->status_account ?: '-' }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div></div>
                <div></div>

                <div class="button-wrapper">
                    @can('delete', $user)
                        <form method="POST" action="#">
                            @csrf
                            @method('DELETE')
                            <x-button text="Delete User" type="submit" class="button-delete"/>
                        </form>
                    @endcan
                </div>

            </div>

        </div>

    </section>

@endsection

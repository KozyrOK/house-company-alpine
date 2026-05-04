@extends('_layouts.app')

@section('title', 'Admin - User Detail')

@section('content')

    <section>

        <h1>User</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="← Back to list" href="{{ route('admin.users.index') }}" class="button-list"/>
                </div>

                <div>
                    <img class="company-image" src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-image-company.webp') }}" alt="user image">
                </div>

                <div class="button-wrapper">
                    <x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/>
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

                <div class="button-wrapper">
                    @if($user->status_account === 'pending')
                        @can('approve', $user)
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="Approve User" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    @endif

                    @can('update', $user)
                        <x-link text="Edit User" href="{{ route('admin.users.edit', $user) }}" class="button-edit"/>
                    @endcan
                </div>

                <div class="button-wrapper">
                    <x-link text="Users Company" href="{{ route('main.companies.index') }}" class="button-list"/>
                </div>

                <div class="button-wrapper">
                    @if($user->companies->count() > 1)
                        <x-link text="User`s companies" href="{{ route('admin.users.index') }}" class="button-list"/>
                    @endif
                    @can('delete', $user)
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="confirmable-form" data-confirm-message="Are you sure you want to delete this user?">
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

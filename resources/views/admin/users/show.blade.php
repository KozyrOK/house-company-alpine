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
                    <img class="company-image" src="{{ $user->image_path ?: asset('images/default-image-company.jpg') }}" alt="user image">
                </div>
                <div class="button-wrapper">
                    <x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/>
                </div>
            </div>

            <table>
                <tr><th class="key-content-item">ID</th><td class="value-content-item" colspan="2">{{ $user->id }}</td></tr>
                <tr><th class="key-content-item">Name</th><td class="value-content-item" colspan="2">{{ $user->first_name }} {{ $user->second_name }}</td></tr>
                <tr><th class="key-content-item">Email</th><td class="value-content-item" colspan="2">{{ $user->email }}</td></tr>
                <tr><th class="key-content-item">Phone</th><td class="value-content-item" colspan="2">{{ $user->phone ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Status</th><td class="value-content-item" colspan="2">{{ $user->status_account ?: '-' }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">
                <div class="action-row-start">
                    <x-link text="Edit User" href="{{ route('admin.users.edit', $user) }}" class="button-edit"/>
                </div>
                <div class="action-row-end">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <x-button text="Delete User" type="submit" class="button-delete"/>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

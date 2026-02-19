@extends('_layouts.app')

@section('title', 'Users')

@section('content')

    <section>

        <h1>Users</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="← Back to Admin Panel" href="{{ route('') }}" class="button-list"/>
            </div>

            <div></div>

            <div class="button-wrapper">
                <x-link text="Create new user" href="{{ route('') }}" class="button-edit"/>
            </div>

        </div>

        <x-filter.filterUser/>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Name</th>
                <th class="key-content-item-center">Email</th>
                <th class="key-content-item-center">Status</th>
                <th class="key-content-item-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $u)
                <tr>
                    <td class="key-content-item">{{ $users->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $u->first_name }} {{ $u->second_name }}</td>
                    <td class="value-content-item">{{ $u->email }}</td>
                    <td class="value-content-item">{{ $u->status_account ?? '-' }}</td>
                    <td class="value-content-item">
                        <x-link text="Detail" class="button-list" href="{{ route('', $u) }}"/>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="value-content-item">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>

    </section>

@endsection

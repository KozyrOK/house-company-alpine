@extends('_layouts.app')

@section('title', 'Users')

@section('content')
    <section>
        <h1>Users</h1>

        <table class="content-item-wrapper mt-4">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Name</th>
                <th class="key-content-item-center">Email</th>
                <th class="key-content-item-center">Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $listedUser)
                <tr>
                    <td class="key-content-item">{{ $index + 1 }}</td>
                    <td class="value-content-item">{{ $listedUser->first_name }} {{ $listedUser->second_name }}</td>
                    <td class="value-content-item">{{ $listedUser->email }}</td>
                    <td class="value-content-item">{{ $listedUser->status_account ?? 'pending' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="value-content-item">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
@endsection

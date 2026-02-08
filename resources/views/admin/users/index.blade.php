@extends('_layouts.app')

@section('title', 'Admin - Users')

@section('content')
    <section>
        <h1>Users</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="← Back to Admin Panel" href="{{ route('admin.index') }}" class="button-list"/>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="number" name="company_id" value="{{ request('company_id') }}" placeholder="Company ID" class="input-field">
                <select name="status_account" class="input-field">
                    <option value="">Any status</option>
                    @foreach(['pending' => 'Pending', 'active' => 'Active', 'blocked' => 'Blocked'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status_account') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-button text="Filter" type="submit" class="button-list"/>
            </form>

            <div class="button-wrapper">
                <x-link text="Create new user" href="{{ route('admin.users.create') }}" class="button-edit"/>
            </div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Name</th>
                <th class="key-content-item-center">Email</th>
                <th class="key-content-item-center">Status</th>
                <th class="key-content-item-center p-2">Actions</th>
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
                        <x-link text="Detail" class="button-list" href="{{ route('admin.users.show', $u) }}"/>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="value-content-item">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->withQueryString()->links() }}
        </div>
    </section>
@endsection

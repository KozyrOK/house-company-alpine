@extends('_layouts.app')

@section('title', 'Admin - Deleted Users')

@section('content')
    <section>
        <h1>Deleted Users</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="← Back to users" href="{{ route('admin.users.index') }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Name</th>
                <th class="key-content-item-center">Email</th>
                <th class="key-content-item-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $u)
                <tr>
                    <td class="key-content-item">{{ $users->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $u->first_name }} {{ $u->second_name }}</td>
                    <td class="value-content-item">{{ $u->email }}</td>
                    <td class="value-content-item">
                        @can('restore', $u)
                            <form method="POST" action="{{ route('admin.users.restore', $u->id) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="Restore" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="value-content-item">No deleted users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
@endsection

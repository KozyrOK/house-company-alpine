@extends('_layouts.app')

@section('title','Admin - Companies')

@section('content')
    <section>
        <h1>Companies</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper"><x-link text="← Back to Admin Panel" href="{{ route('admin.index') }}" class="button-list"/></div>

            <form method="GET" action="{{ route('admin.companies.index') }}" class="filter-form">
                <input type="text" name="city" value="{{ request('city') }}" placeholder="City" class="input-field">
                <x-button text="Filter" type="submit" class="button-list"/>
            </form>

            <div class="button-wrapper"><x-link text="Create New Company" href="{{ route('admin.companies.create') }}" class="button-edit"/></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Name</th>
                <th class="key-content-item-center">City</th>
                <th class="key-content-item-center">Users</th>
                <th class="key-content-item-center">Posts</th>
                <th class="key-content-item-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $index => $c)
                <tr>
                    <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $c->name }}</td>
                    <td class="value-content-item">{{ $c->city ?: '-' }}</td>
                    <td class="value-content-item">{{ $c->users_count ?? '-' }}</td>
                    <td class="value-content-item">{{ $c->posts_count ?? '-' }}</td>
                    <td><x-link text="Detail" class="button-list" href="{{ route('admin.companies.show', $c) }}"/></td>
                </tr>
            @empty
                <tr><td colspan="6" class="value-content-item">No companies found.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="content-actions">{{ $companies->withQueryString()->links() }}</div>
    </section>
@endsection

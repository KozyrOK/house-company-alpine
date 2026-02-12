@extends('_layouts.app')

@section('title','Admin - Posts')

@section('content')
    <section>
        <h1>Posts</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper"><x-link text="← Back to Admin Panel" href="{{ route('admin.index') }}" class="button-list"/></div>

            <form method="GET" action="{{ route('admin.posts.index') }}" class="filter-form">
                <input type="number" name="company_id" value="{{ request('company_id') }}" placeholder="Company ID" class="input-field">
                <input type="number" name="user_id" value="{{ request('user_id') }}" placeholder="User ID" class="input-field">
                <select name="status" class="input-field">
                    <option value="">Any status</option>
                    @foreach(['draft','future','pending','publish','trash'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field">
                <x-button text="Filter" type="submit" class="button-list"/>
            </form>

            <div class="button-wrapper"><x-link text="Create Post" href="{{ route('admin.posts.create') }}" class="button-edit"/></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Title</th>
                <th class="key-content-item-center">Company</th>
                <th class="key-content-item-center">Author</th>
                <th class="key-content-item-center">Status</th>
                <th class="key-content-item-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($posts as $index => $p)
                <tr>
                    <td class="key-content-item">{{ $posts->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $p->title }}</td>
                    <td class="value-content-item">{{ $p->company?->name ?? '-' }}</td>
                    <td class="value-content-item">{{ trim(($p->user?->first_name ?? '').' '.($p->user?->second_name ?? '')) ?: '-' }}</td>
                    <td class="value-content-item">{{ $p->status }}</td>
                    <td class="value-content-item"><x-link text="Detail" class="button-list" href="{{ route('admin.posts.show', $p) }}"/></td>
                </tr>
            @empty
                <tr><td colspan="6" class="value-content-item">No posts found.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="content-actions">{{ $posts->withQueryString()->links() }}</div>
    </section>
@endsection

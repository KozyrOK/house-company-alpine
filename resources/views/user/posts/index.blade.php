@extends('_layouts.app')

@section('title','Main - Posts')

@section('content')

    <section>

        <h1>Posts</h1>

        @php $role = auth()->user()->roleIn($company); @endphp
        <div class="top-crud-wrapper-four">

            <div class="button-wrapper">
                <x-link text="← Back to Main Panel" href="{{ route('main.index') }}" class="button-list"/>
            </div>

            @if(in_array($role, ['admin','company_head'], true))
                <div class="button-wrapper"><x-link text="Approve" href="{{ route('action-approve.posts-approve') }}" class="button-list"/></div>
                <div class="button-wrapper"><x-link text="Trash" href="{{ route('admin.posts.trash') }}" class="button-trash"/></div>
                <div class="button-wrapper"><x-link text="Create Post" href="{{ route('admin.posts.create') }}" class="button-edit"/></div>
            @else
                <div></div><div></div><div></div>
            @endif

        </div>

        <x-filter.filterPost/>

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
                    <td class="value-content-item"><x-link text="Detail" class="button-list" href="{{ route('main.posts.show', [$company, $p]) }}"/></td>
                </tr>
            @empty
                <tr><td colspan="6" class="value-content-item">No posts found.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $posts->links() }}</div>

    </section>

@endsection

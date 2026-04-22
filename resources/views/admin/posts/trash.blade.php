@extends('_layouts.app')

@section('title','Admin - Deleted Posts')

@section('content')
    <section>
        <h1>Deleted Posts</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="← Back to posts" href="{{ route('admin.posts.index') }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Title</th>
                <th class="key-content-item-center">Company</th>
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
                    <td class="value-content-item">{{ $p->status }}</td>
                    <td class="value-content-item">
                        @can('restore', $p)
                            <form method="POST" action="{{ route('admin.posts.restore', $p->id) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="Restore" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="value-content-item">No deleted posts found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
@endsection

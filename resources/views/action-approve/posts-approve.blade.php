@extends('_layouts.app')

@section('title','Action Approve - Posts')

@section('content')

    <section>
        <h1>Posts Approve</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="← Back to Approve Panel" href="{{ route('action-approve.index') }}" class="button-list"/>
            </div>

            <div></div>

            <div></div>

        </div>

        <table class="content-item-wrapper">
            <thead><tr><th class="key-content-item-center">#</th><th class="key-content-item-center">Title</th><th class="key-content-item-center">Company</th><th class="key-content-item-center">Actions</th></tr></thead><tbody>
            @forelse($posts as $index => $p)
                <tr><td class="key-content-item">{{ $posts->firstItem() + $index }}</td><td class="value-content-item">{{ $p->title }}</td><td class="value-content-item">{{ $p->company?->name ?? '-' }}</td><td class="value-content-item"><x-link text="Approve" class="button-edit" href="{{ route('admin.posts.show', $p) }}"/></td></tr>
            @empty <tr><td colspan="4" class="value-content-item">No posts found.</td></tr>@endforelse
            </tbody>
        </table>
    </section>

@endsection

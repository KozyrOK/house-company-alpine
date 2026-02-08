@extends('_layouts.app')

@section('title', 'Admin - Post Detail')

@section('content')
    <section>
        <h1>Post</h1>

        <div class="content-item-wrapper">
            <div class="top-crud-wrapper">
                <div class="button-wrapper"><x-link text="← Back to list" href="{{ route('admin.posts.index') }}" class="button-list"/></div>
                <div><img class="company-image" src="{{ asset('images/default-image-company.jpg') }}" alt="post image"></div>
                <div class="button-wrapper"><x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/></div>
            </div>

            <table>
                <tr><th class="key-content-item">ID</th><td colspan="2" class="value-content-item">{{ $post->id }}</td></tr>
                <tr><th class="key-content-item">Title</th><td colspan="2" class="value-content-item">{{ $post->title }}</td></tr>
                <tr><th class="key-content-item">Company</th><td colspan="2" class="value-content-item">{{ $post->company?->name ?? '-' }}</td></tr>
                <tr><th class="key-content-item">Status</th><td colspan="2" class="value-content-item">{{ $post->status }}</td></tr>
                <tr><th class="key-content-item">Content</th><td colspan="2" class="value-content-item">{{ $post->content }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">
                <div class="flex justify-start"><x-link text="Edit Post" href="{{ route('admin.posts.edit', $post) }}" class="button-edit"/></div>
                <div class="flex justify-end">
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                        @csrf
                        @method('DELETE')
                        <x-button text="Delete Post" type="submit" class="button-delete"/>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

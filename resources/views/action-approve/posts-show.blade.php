@extends('_layouts.app')
@section('title','Approve Post Detail')
@section('content')
    <section>
        <h1>Post approval detail</h1>
        <div class="bottom-crud-wrapper"><div class="button-wrapper"><x-link text="Back to list" href="{{ route('action-approve.posts-approve') }}" class="button-list"/></div><div></div><div class="button-wrapper"><x-link text="Approve menu" href="{{ route('action-approve.index') }}" class="button-list"/></div></div>
        <table class="content-item-wrapper"><tr><th class="key-content-item">Title</th><td class="value-content-item">{{ $post->title }}</td></tr><tr><th class="key-content-item">Author</th><td class="value-content-item">{{ $post->user?->first_name }} {{ $post->user?->second_name }}</td></tr><tr><th class="key-content-item">Email</th><td class="value-content-item">{{ $post->user?->email }}</td></tr><tr><th class="key-content-item">Content</th><td class="value-content-item">{{ $post->content }}</td></tr></table>
        <div class="bottom-crud-wrapper"><div class="button-wrapper"><form method="POST" action="{{ route('action-approve.posts-approve-do',$post) }}">@csrf @method('PATCH')<x-button text="Approve" type="submit" class="button-edit"/></form></div><div></div><div class="button-wrapper"><form method="POST" action="{{ route('action-approve.posts-reject-do',$post) }}">@csrf @method('PATCH')<x-button text="Reject" type="submit" class="button-delete"/></form></div></div>
    </section>
@endsection

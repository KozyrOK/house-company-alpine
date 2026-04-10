@extends('_layouts.app')

@section('title', 'Main - Post Detail')

@section('content')

    <section>

        <h1>Post</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="← Back to list" href="{{ route('main.posts.index', $company) }}" class="button-list"/>
                </div>

                <div>
                    <img class="company-image" src="{{ asset('images/default-image-company.jpg') }}" alt="post image">
                </div>

                <div class="button-wrapper">
                    <x-link text="Main Menu" href="{{ route('main.index') }}" class="button-list"/>
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $post->id }}</td></tr>
                <tr><th class="key-content-item">Title</th><td class="value-content-item">{{ $post->title }}</td></tr>
                <tr><th class="key-content-item">Company</th><td class="value-content-item">{{ $post->company?->name ?? '-' }}</td></tr>
                <tr><th class="key-content-item">Status</th><td class="value-content-item">{{ $post->status }}</td></tr>
                <tr><th class="key-content-item">Content</th><td class="value-content-item">{{ $post->content }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

            <div></div>
            <div></div>

            </div>

        </div>

    </section>

@endsection

@extends('_layouts.app')

@section('title', $post->title)

@section('content')
    <section>
        <h1>{{ $post->title }}</h1>
        <p class="text-muted">
            Company: {{ $post->company->name ?? $company->name ?? '—' }}
            · User: {{ $post->user->first_name ?? '' }} {{ $post->user->second_name ?? '' }}
            · Status: {{ $post->status }}
        </p>

        <article class="content-item-wrapper content-article">
            {{ $post->content }}
        </article>

        @if($company)
            <div class="content-actions">
                <x-link text="Back to list" href="{{ route('main.posts.index', $company) }}" class="button-list"/>
            </div>
        @endif
    </section>
@endsection

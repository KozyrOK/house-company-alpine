@extends('_layouts.app')

@section('title', 'Forum')

@section('content')

    <section>

        <h1>{{ __('app.pages.forum') }}</h1>

        <p>
            {!! nl2br(__('app.pages.forum_text1')) !!}
        </p>

    </section>

@endsection

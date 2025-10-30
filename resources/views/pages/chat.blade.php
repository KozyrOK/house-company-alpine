@extends('_layouts.app')

@section('title', 'Chat')

@section('content')

    <section>

        <h1>{{ __('app.pages.chat') }}</h1>

        <p>
            {!! nl2br(__('app.pages.chat_text1')) !!}
        </p>

    </section>

@endsection

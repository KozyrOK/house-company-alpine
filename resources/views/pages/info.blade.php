@extends('_layouts.app')

@section('title', 'info')

@section('content')

<section>

    <h1>{{ __('app.pages.info_text1') }}</h1>

    <p>
        {!! nl2br(__('app.pages.info_text2')) !!}
    </p>

</section>

@endsection

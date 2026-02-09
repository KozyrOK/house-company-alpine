@extends('_layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section>
        <h1>{{ __('app.pages.user_dashboard') }}</h1>

        <div class="mt-6 grid gap-6">
            @php($user = $user ?? auth()->user())
            @include('user.users.show', ['user' => $user])
            @include('user.users.edit', ['user' => $user])
        </div>

    </section>
@endsection

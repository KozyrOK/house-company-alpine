@extends('_layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section>
        <h1>{{ __('app.pages.user_dashboard') }}</h1>

        @php($user = $user ?? auth()->user())
        @php($isEditMode = request()->boolean('edit'))

        <div class="dashboard-grid">

            @include('user.users.show', ['user' => $user])

            @if($isEditMode)
                @include('user.users.edit', ['user' => $user])
            @else
                <div class="content-item-wrapper">
                    <h2 class="section-title">Profile actions</h2>

                    <div class="form-actions section-spacing">
                        <x-link text="Edit" href="{{ route('dashboard', ['edit' => 1]) }}" class="button-edit"/>
                    </div>
                </div>
            @endif
        </div>

    </section>
@endsection

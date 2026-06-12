@extends('_layouts.app')

@section('title', 'User Detail')

@section('content')

    <section>

        <h1>{{__('app.users.user')}}</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="app.buttons.back_to_list" href="{{ isset($company) ? route('main.users.index') : route('dashboard') }}" class="button-list"/>
                </div>

                <div>
                    <img class="company-image" src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-image-company.webp') }}" alt="user image">
                </div>

                <div class="button-wrapper">
                    <x-link text="{{ isset($company) ? 'app.buttons.main_menu' : 'app.buttons.dashboard' }}"
                            href="{{ isset($company) ? route('main.index') : route('dashboard') }}"
                            class="button-list"/>
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $user->id }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.name')}}</th><td class="value-content-item">{{ $user->first_name }} {{ $user->second_name }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.e_mail')}}</th><td class="value-content-item">{{ $user->email }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.phone')}}</th><td class="value-content-item">{{ $user->phone ?: '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.status')}}</th><td class="value-content-item">{{ $user->status_account ?: '-' }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div></div>
                <div></div>

                <div class="button-wrapper">
                    @can('delete', $user)
                        <form method="POST" action="#">
                            @csrf
                            @method('DELETE')
                            <x-button text="app.buttons.delete_user" type="submit" class="button-delete"/>
                        </form>
                    @endcan
                </div>

            </div>

        </div>

    </section>

@endsection

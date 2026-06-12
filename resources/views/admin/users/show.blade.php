@extends('_layouts.app')

@section('title', 'Admin - User Detail')

@section('content')

    <section>

        <h1>{{__('app.users.user')}}</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="app.buttons.back_to_list" href="{{ route('admin.users.index') }}" class="button-list"/>
                </div>

                <div>
                    <img class="company-image" src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-image-company.webp') }}" alt="user image">
                </div>

                <div class="button-wrapper">
                    <x-link text="app.buttons.admin_menu" href="{{ route('admin.index') }}" class="button-list"/>
                </div>

            </div>
            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $user->id }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.name')}}</th><td class="value-content-item">{{ $user->first_name }} {{ $user->second_name }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.e_mail')}}</th><td class="value-content-item">{{ $user->email }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.phone')}}</th><td class="value-content-item">{{ $user->phone ?: '-' }}</td></tr>
                @if(auth()->user()->isSuperAdmin())
                    <tr><th class="key-content-item">{{__('app.tables.account_status')}}</th><td class="value-content-item">{{ $user->status_account ?: '-' }}</td></tr>
                @else
                    <tr><th class="key-content-item">{{__('app.tables.membership_status')}}</th><td class="value-content-item">{{ $currentMembership?->status_membership ?: '-' }}</td></tr>
                @endif
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    @if($user->status_account === 'pending')
                        @can('approve', $user)
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="app.buttons.approve_user" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    @endif

                    @can('update', $user)
                            @if(auth()->user()->isSuperAdmin())
                                <x-link text="app.buttons.users_companies" href="{{ route('admin.users.companies', $user) }}" class="button-list"/>
                            @elseif(currentCompany())
                                @can('excludeFromCompany', [$user, currentCompany()])
                                    <form method="POST" action="{{ route('admin.users.companies.exclusion', [$user, currentCompany()]) }}" class="confirmable-form" data-confirm-message="{{ __('app.confirm.exclusion_user') }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-button text="app.buttons.exclusion_user" type="submit" class="button-delete"/>
                                    </form>
                                @endcan
                            @endif
                    @endcan
                </div>

                <div class="button-wrapper">
                    <x-link text="app.buttons.users_companies" href="{{ route('admin.users.companies', $user) }}" class="button-list"/>
                </div>

                <div class="button-wrapper">
                    @can('delete', $user)
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="confirmable-form" data-confirm-message="{{ __('app.confirm.delete_user') }}">
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

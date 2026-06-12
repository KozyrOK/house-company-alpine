@extends('_layouts.app')

@section('title', 'Company')

@section('content')

    <section>

        <h1>{{__('app.companies.company')}}</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper"></div>

                <div>
                    <img alt="logo" src="{{ $company->getLogoUrlAttribute() }}" class="company-image">
                </div>

                <div class="button-wrapper">
{{--                    <x-link text="app.buttons.dashboard" href="{{ route('dashboard') }}" class="button-list"/>--}}
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $company->id }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.name')}}</th><td class="value-content-item">{{ $company->name }}</td></tr>
                <tr><th class="key-content-item">{{__('app.companies.your_role_in_company')}}</th><td class="value-content-item">{{ auth()->user()->roleIn($company) }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.address')}}</th><td class="value-content-item">{{ $company->address ?: '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.city')}}</th><td class="value-content-item">{{ $company->city ?: '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.description')}}</th><td class="value-content-item">{{ $company->description ?: '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.users_count')}}</th><td class="value-content-item">{{ $company->users_count }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.posts_count')}}</th><td class="value-content-item">{{ $company->posts_count }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    @if(auth()->user()->hasRole('admin', $company->id))
                        <x-link text="app.buttons.edit_company" href="{{ route('admin.companies.edit', $company) }}" class="button-edit"/>
                    @endif
                </div>

                <div class="button-wrapper">
                    @can('excludeFromCompany', [auth()->user(), $company])
                        <form method="POST" action="{{ route('company.exclusion') }}" class="confirmable-form" data-confirm-message="{{ __('app.confirm.exclusion_user') }}">
                            @csrf
                            @method('DELETE')
                            <x-button text="app.buttons.exclusion" type="submit" class="button-delete"/>
                        </form>
                    @endcan
                </div>

                <div class="button-wrapper">
                    @if(auth()->user()->hasRole('admin', $company->id))
                        <x-link text="app.buttons.company_users" href="{{ route('admin.users.index') }}" class="button-list"/>
                    @else
                        <x-link text="app.buttons.company_users" href="{{ route('main.users.index') }}" class="button-list"/>
                    @endif
                </div>

            </div>

            @if(auth()->user()->hasRole('admin', $company->id))
                <div x-data="{ open: false }">
                    <div class="flex items-center justify-evenly mb-3">
                        <h3>{{__('app.users.request_add_admin')}}</h3>
                        <button type="button" @click="open = !open" class="button-extend">
                            <span x-show="!open">+</span>
                            <span x-show="open">×</span>
                        </button>
                    </div>

                    <div x-show="open" x-transition>
                        <form method="POST" action="{{ route('company.request-admin') }}">
                            @csrf
                            <select name="user_id" class="input-field" required>
                                <option value="">{{__('app.users.select_user')}}</option>
                                @foreach(($adminCandidates ?? collect()) as $candidate)
                                    <option value="{{ $candidate->id }}">
                                        {{ $candidate->first_name }} {{ $candidate->second_name }} ({{ $candidate->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="button-wrapper">
                                <x-button text="app.buttons.add_admin" type="submit" class="button-edit"/>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>

    </section>

@endsection

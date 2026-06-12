@extends('_layouts.app')

@section('title', 'Admin - Company Detail')

@section('content')

    <section>

        <h1>{{__('app.tables.company')}}</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="app.buttons.back_to_list" href="{{ route('admin.companies.index') }}" class="button-list"/>
                </div>

                <div>
                    <img alt="logo" src="{{ route('admin.companies.logo', $company) }}" class="company-image">
                </div>

                <div class="button-wrapper">
                    <x-link text="app.buttons.admin_menu" href="{{ route('admin.index') }}" class="button-list"/>
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $company->id }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.name')}}</th><td class="value-content-item">{{ $company->name }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.address')}}</th><td class="value-content-item">{{ $company->address ?: '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.city')}}</th><td class="value-content-item">{{ $company->city ?: '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.description')}}</th><td class="value-content-item">{{ $company->description ?: '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.users_count')}}</th><td class="value-content-item">{{ $company->users_count }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.posts_count')}}</th><td class="value-content-item">{{ $company->posts_count }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    @can('update', $company)
                        <x-link text="app.buttons.edit_company" href="{{ route('admin.companies.edit', $company) }}" class="button-edit"/>
                    @endcan
                </div>

                <div></div>

                <div class="button-wrapper">
                    @can('delete', $company)
                        <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" class="confirmable-form" data-confirm-message="{{ __('app.confirm.delete_company') }}">
                            @csrf
                            @method('DELETE')
                            <x-button text="app.buttons.delete_company" type="submit" class="button-delete"/>
                        </form>
                    @endcan
                </div>

            </div>

        </div>

    </section>

@endsection

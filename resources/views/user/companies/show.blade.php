@extends('_layouts.app')

@section('title', 'Company Detail')

@section('content')

    <section>

        <h1>Company</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="← Back to list" href="{{ route('main.companies.index') }}" class="button-list"/>
                </div>

                <div>
                    <img alt="logo" src="{{ $company->logo_url }}" class="company-image">
                </div>

                <div class="button-wrapper">
                    <x-link text="Main Menu" href="{{ route('main.index') }}" class="button-list"/>
                </div>

            </div>

            <table>
                <tr><th class="key-content-item">ID</th><td colspan="2" class="value-content-item">{{ $company->id }}</td></tr>
                <tr><th class="key-content-item">Name</th><td colspan="2" class="value-content-item">{{ $company->name }}</td></tr>
                <tr><th class="key-content-item">Address</th><td colspan="2" class="value-content-item">{{ $company->address ?: '-' }}</td></tr>
                <tr><th class="key-content-item">City</th><td colspan="2" class="value-content-item">{{ $company->city ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Description</th><td colspan="2" class="value-content-item">{{ $company->description ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Users count</th><td colspan="2" class="value-content-item">{{ $company->users_count }}</td></tr>
                <tr><th class="key-content-item">Posts count</th><td colspan="2" class="value-content-item">{{ $company->posts_count }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    @can('update', $company)
                        <x-link text="Company Users" href="{{ route('main.users.index', $company) }}" class="button-edit"/>
                    @endcan
                </div>

            </div>

        </div>

    </section>

@endsection

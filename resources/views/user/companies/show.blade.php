@extends('_layouts.app')

@section('title', 'Company')

@section('content')

    <section>

        <h1>Company</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="← Back" href="{{ route('main.index') }}" class="button-list"/>
                </div>

                <div>
                    <img alt="logo" src="{{ $company->logo_url }}" class="company-image">
                </div>

                <div class="button-wrapper">
                    <x-link text="Dashboard" href="{{ route('dashboard') }}" class="button-list"/>
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $company->id }}</td></tr>
                <tr><th class="key-content-item">Name</th><td class="value-content-item">{{ $company->name }}</td></tr>
                <tr><th class="key-content-item">Address</th><td class="value-content-item">{{ $company->address ?: '-' }}</td></tr>
                <tr><th class="key-content-item">City</th><td class="value-content-item">{{ $company->city ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Description</th><td class="value-content-item">{{ $company->description ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Users count</th><td class="value-content-item">{{ $company->users_count }}</td></tr>
                <tr><th class="key-content-item">Posts count</th><td class="value-content-item">{{ $company->posts_count }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    @if(auth()->user()->hasRole('admin', $company->id))
                        <x-link text="Edit Company" href="{{ route('admin.companies.edit', $company) }}" class="button-edit"/>
                    @endif
                </div>

                <div></div>

                <div class="button-wrapper">
                    <x-link text="Company Users" href="{{ route('main.users.index', $company) }}" class="button-list"/>
                </div>

            </div>

        </div>

    </section>

@endsection

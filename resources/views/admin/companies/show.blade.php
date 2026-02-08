@extends('_layouts.app')

@section('title', 'Admin - Company Detail')

@section('content')
    <section>
        <h1>Company</h1>

        <div class="content-item-wrapper">
            <div class="top-crud-wrapper">
                <div class="button-wrapper"><x-link text="← Back to list" href="{{ route('admin.companies.index') }}" class="button-list"/></div>
                <div><img alt="logo" src="{{ route('admin.companies.logo', $company) }}" class="company-image"></div>
                <div class="button-wrapper"><x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/></div>
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
                <div class="flex justify-start"><x-link text="Edit Company" href="{{ route('admin.companies.edit', $company) }}" class="button-edit"/></div>
                <div class="flex justify-end">
                    <form method="POST" action="{{ route('admin.companies.destroy', $company) }}">
                        @csrf
                        @method('DELETE')
                        <x-button text="Delete Company" type="submit" class="button-delete"/>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

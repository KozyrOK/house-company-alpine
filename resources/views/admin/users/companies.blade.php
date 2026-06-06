@extends('_layouts.app')

@section('title','User`s companies')

@section('content')

    <section>
        <h1>User`s companies</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="← Back to user" href="{{ route('admin.users.show', $user) }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <x-filter.filterCompany/>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">№</th>
                <th class="key-content-item-center">Company name</th>
                <th class="key-content-item-center">City</th>
                <th class="key-content-item-center">User's role</th>
                <th class="key-content-item-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $index => $company)
                <tr>
                    <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $company->name }}</td>
                    <td class="value-content-item">{{ $company->city ?: '-' }}</td>
                    <td class="value-content-item">{{ $company->pivot->role }}</td>
                    <td class="value-content-item"><x-link text="Detail" class="button-list" href="{{ route('admin.companies.show', $company) }}"/></td>
                </tr>
            @empty
                <tr><td colspan="5" class="value-content-item">No companies found.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $companies->links() }}</div>
    </section>

@endsection

@extends('_layouts.app')

@section('title','Admin - Companies')

@section('content')

    <section>

        <h1>{{__('app.tables.companies')}}</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_admin_panel" href="{{ route('admin.index') }}" class="button-list"/>
            </div>

            <div class="button-wrapper">
                @if(auth()->user()->isSuperAdmin())
                    <x-link text="app.buttons.trash" href="{{ route('admin.companies.trash') }}" class="button-trash"/>
                @endif
            </div>

            <div class="button-wrapper action-row-end">
                @can('create', \App\Models\Company::class)
                    <x-link text="app.buttons.create_company" href="{{ route('admin.companies.create') }}" class="button-edit"/>
                @endcan
            </div>

        </div>

        <x-filter.filterCompany/>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.name')}}</th>
                <th class="key-content-item-center">{{__('app.tables.city')}}</th>
                <th class="key-content-item-center">{{__('app.tables.status')}}</th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $index => $c)
                <tr>
                    <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $c->name }}</td>
                    <td class="value-content-item">{{ $c->city ?: '-' }}</td>
                    <td class="value-content-item">{{ $c->status_company }}</td>
                    <td><x-link text="app.buttons.detail" class="button-list" href="{{ route('admin.companies.show', $c) }}"/></td>
                </tr>
            @empty
                <tr><td colspan="6" class="value-content-item">{{__('app.companies.no_companies_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="pagination-list">{{ $companies->links() }}</div>

    </section>

@endsection

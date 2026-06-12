@extends('_layouts.app')

@section('title','Admin - Deleted Companies')

@section('content')
    <section>
        <h1>{{__('app.tables.deleted_companies')}}</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_companies" href="{{ route('admin.companies.index') }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.name')}}</th>
                <th class="key-content-item-center">{{__('app.tables.city')}}</th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $index => $c)
                <tr>
                    <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $c->name }}</td>
                    <td class="value-content-item">{{ $c->city ?: '-' }}</td>
                    <td class="value-content-item">
                        @can('restore', $c)
                            <form method="POST" action="{{ route('admin.companies.restore', $c->id) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="app.buttons.restore" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="value-content-item">{{__('app.companies.no_deleted_companies_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
@endsection

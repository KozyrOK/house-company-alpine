@extends('_layouts.app')

@section('title','Main - Companies')

@section('content')

    <section>

        <h1>{{__('app.companies.companies')}}</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper"><x-link text="app.buttons.back_to_main_panel" href="{{ route('main.index') }}" class="button-list"/></div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.name')}}</th>
                <th class="key-content-item-center">{{__('app.tables.city')}}/th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $index => $c)
                <tr>
                    <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $c->name }}</td>
                    <td class="value-content-item">{{ $c->city ?: '-' }}</td>
                    <td><x-link text="app.buttons.detail" class="button-list" href="{{ route('main.companies.show') }}"/></td>
                </tr>
            @empty
                <tr><td colspan="4" class="value-content-item">{{__('app.companies.no_companies_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="pagination-list">{{ $companies->links() }}</div>

    </section>

@endsection

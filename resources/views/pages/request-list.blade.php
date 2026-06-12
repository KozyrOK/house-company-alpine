@extends('_layouts.app')

@section('title', 'Request list')

@section('content')
    <section>
        <h1>{{__('app.tables.request_list')}}</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_dashboard" href="{{ route('dashboard') }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.company')}}</th>
                <th class="key-content-item-center">{{__('app.tables.city')}}</th>
                <th class="key-content-item-center">{{__('app.tables.request_list')}}</th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
                @forelse($companies as $index => $company)
                    <tr>
                        <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                        <td class="value-content-item">{{ $company->name }}</td>
                        <td class="value-content-item">{{ $company->city ?: '-' }}</td>
                        <td class="value-content-item">{{ $company->pivot->status_membership }}</td>
                        <td class="value-content-item">
                            <form method="POST" action="{{ route('company.request-list.delete', $company) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="app.buttons.delete" type="submit" class="button-delete"/>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="value-content-item">{{__('app.tables.no_requests_found')}}</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-list">{{ $companies->links() }}</div>
    </section>
@endsection

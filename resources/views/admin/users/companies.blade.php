@extends('_layouts.app')

@section('title','User`s companies')

@section('content')

    <section>
        <h1>{{__('app.users.users_companies')}} - {{ $user->first_name }} {{ $user->second_name }}</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_user" href="{{ route('admin.users.show', $user) }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <x-filter.filterCompany/>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">№</th>
                <th class="key-content-item-center">{{__('app.tables.company')}}</th>
                <th class="key-content-item-center">{{__('app.tables.city')}}</th>
                <th class="key-content-item-center">{{__('app.tables.users_role')}}</th>
                <th class="key-content-item-center">{{__('app.tables.membership_status')}}</th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $index => $company)
                <tr>
                    <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $company->name }}</td>
                    <td class="value-content-item">{{ $company->city ?: '-' }}</td>
                    <td class="value-content-item">{{ $company->pivot->role }}</td>
                    <td class="value-content-item">{{ $company->pivot->status_membership }}</td>
                    <td class="value-content-item">
                        <div class="button-wrapper">
                            <x-link text="app.buttons.detail" class="button-list" href="{{ route('admin.companies.show', $company) }}"/>
                            @can('excludeFromCompany', [$user, $company])
                                <form method="POST" action="{{ route('admin.users.companies.exclusion', [$user, $company]) }}" class="confirmable-form" data-confirm-message="{{ __('app.confirm.exclusion_user') }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-button text="app.buttons.exclusion_user" type="submit" class="button-delete"/>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="value-content-item">{{__('app.companies.no_companies_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $companies->links() }}</div>
    </section>

@endsection

@extends('_layouts.app')

@section('title', 'Users')

@section('content')

    <section>

        <h1>{{__('app.users.users')}}</h1>

        @php $role = auth()->user()->roleIn($company); @endphp
        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_main_panel" href="{{ route('main.index') }}" class="button-list"/>
            </div>

            @if(in_array($role, ['admin','company_head'], true))

            @else
                <div></div>
            @endif

            <div></div>

        </div>

        <x-filter.filterUser/>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.name')}}</th>
                <th class="key-content-item-center">{{__('app.tables.e_mail')}}</th>
                <th class="key-content-item-center">{{__('app.tables.company')}}</th>
                <th class="key-content-item-center">{{__('app.tables.membership_status')}}</th>
                <th class="key-content-item-center">{{__('app.tables.role')}}</th>
                <th class="key-content-item-center">{{__('app.tables.account_status')}}</th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $u)
                <tr>
                    <td class="key-content-item">{{ $users->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $u->first_name }} {{ $u->second_name }}</td>
                    <td class="value-content-item">{{ $u->email }}</td>
                    <td class="value-content-item">{{ $u->membership_company_name ?? ($company->name ?? '-') }}</td>
                    <td class="value-content-item">{{ $u->membership_status ?? $u->pivot?->status_membership ?? '-' }}</td>
                    <td class="value-content-item">{{ $u->membership_role ?? $u->pivot?->role ?? '-' }}</td>
                    <td class="value-content-item">{{ $u->status_account ?? '-' }}</td>
                    <td class="value-content-item">
                        <x-link text="app.buttons.detail" class="button-list" href="{{ route('main.users.show', $u) }}"/>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="value-content-item">{{__('app.users.no_users_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="pagination-list">{{ $users->links() }}</div>

    </section>

@endsection

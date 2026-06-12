@extends('_layouts.app')
@section('title','User Approval Detail')
@section('content')

    <section>
        <h1>{{__('app.action_approve.user_approval_detail')}}</h1>

        <div class="bottom-crud-wrapper">
            <div class="button-wrapper"><x-link text="app.buttons.back_to_list" href="{{ route('action-approve.users-approve') }}" class="button-list"/></div>
            <div></div>
            <div></div>
        </div>

        <x-filter.filterUser/>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">№</th>
                <th class="key-content-item-center">{{__('app.action_approve.action_approve')}}</th>
                <th class="key-content-item-center">{{__('app.action_approve.name')}}</th>
                <th class="key-content-item-center">{{__('app.action_approve.email')}}</th>
                <th class="key-content-item-center">{{__('app.action_approve.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="key-content-item">1</td>
                <td class="value-content-item">{{ $approval->status_membership === 'pending_admin' ? 'add admin' : 'add user' }}</td>
                <td class="value-content-item">{{ $user->first_name }} {{ $user->second_name }}</td>
                <td class="value-content-item">{{ $user->email }}</td>
                <td class="value-content-item">

                        <div class="button-wrapper">
                            <form method="POST" action="{{ route('action-approve.users-approve-do', $user) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="company_id" value="{{ $approval->company_id }}">
                                <x-button text="app.buttons.approve" type="submit" class="button-edit"/>
                            </form>
                        </div>
                        <div class="button-wrapper">
                            <form method="POST" action="{{ route('action-approve.users-reject-do', $user) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="company_id" value="{{ $approval->company_id }}">
                                <x-button text="app.buttons.reject" type="submit" class="button-delete"/>
                            </form>
                        </div>

                </td>
            </tr>
            </tbody>
        </table>

    </section>
@endsection

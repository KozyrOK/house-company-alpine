@extends('_layouts.app')
@section('title','Action Approve')
@section('content')

    <section>

    <h1>{{__('app.action_approve.action_approve')}}</h1>

    <x-filter.filterUser/>

    <table class="content-item-wrapper">
        <thead>
        <tr>
            <th class="key-content-item-center">№</th>
            <th class="key-content-item-center">{{__('app.action_approve.approve_type')}}</th>
            <th class="key-content-item-center">{{__('app.action_approve.name')}}</th>
            <th class="key-content-item-center">{{__('app.action_approve.email')}}</th>
            <th class="key-content-item-center">{{__('app.action_approve.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($approvals as $index => $approval)
            <tr>
                <td class="key-content-item">{{ $approvals->firstItem() + $index }}</td>
                <td class="value-content-item">{{ $approval->status_membership === 'pending_admin' ? 'add admin' : 'add user' }}</td>
                <td class="value-content-item">{{ $approval->first_name }} {{ $approval->second_name }}</td>
                <td class="value-content-item">{{ $approval->email }}</td>
                <td class="value-content-item">
                    <x-link text="app.buttons.detail" class="button-edit" href="{{ route('action-approve.users-show', ['user' => $approval->user_id, 'company_id' => $approval->company_id]) }}"/>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="value-content-item">{{__('app.action_approve.no_approvals_found')}}</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $approvals->links() }}</div>

    </section>

@endsection

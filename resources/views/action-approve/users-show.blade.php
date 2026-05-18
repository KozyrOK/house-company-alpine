@extends('_layouts.app')
@section('title','Approve User Detail')
@section('content')

    <section>
        <h1>User approval detail</h1>
        <div class="bottom-crud-wrapper"><div class="button-wrapper"><x-link text="Back to list" href="{{ route('action-approve.users-approve') }}" class="button-list"/></div><div></div><div class="button-wrapper"><x-link text="Approve menu" href="{{ route('action-approve.index') }}" class="button-list"/></div></div>
        <table class="content-item-wrapper"><tr><th class="key-content-item">Name</th><td class="value-content-item">{{ $user->first_name }} {{ $user->second_name }}</td></tr><tr><th class="key-content-item">Email</th><td class="value-content-item">{{ $user->email }}</td></tr></table>
        <div class="bottom-crud-wrapper"><div class="button-wrapper"><form method="POST" action="{{ route('action-approve.users-approve-do',$user) }}">@csrf @method('PATCH') <input type="hidden" name="company_id" value="{{ request('company_id', currentCompany()?->id) }}"><x-button text="Approve" type="submit" class="button-edit"/></form></div><div></div><div class="button-wrapper"><form method="POST" action="{{ route('action-approve.users-reject-do',$user) }}">@csrf @method('PATCH') <input type="hidden" name="company_id" value="{{ request('company_id', currentCompany()?->id) }}"><x-button text="Reject" type="submit" class="button-delete"/></form></div></div>
    </section>
@endsection

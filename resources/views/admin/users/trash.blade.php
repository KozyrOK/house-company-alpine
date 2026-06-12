@extends('_layouts.app')

@section('title', 'Admin - Deleted Users')

@section('content')
    <section>
        <h1>{{__('app.users.deleted_user')}}</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_users" href="{{ route('admin.users.index') }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.name')}}</th>
                <th class="key-content-item-center">{{__('app.tables.e_mail')}}</th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $u)
                <tr>
                    <td class="key-content-item">{{ $users->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $u->first_name }} {{ $u->second_name }}</td>
                    <td class="value-content-item">{{ $u->email }}</td>
                    <td class="value-content-item">
                        @can('restore', $u)
                            <form method="POST" action="{{ route('admin.users.restore', $u->id) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="app.buttons.restore" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="value-content-item">{{__('app.users.no_deleted_users_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="pagination-list">{{ $users->links() }}</div>
    </section>
@endsection

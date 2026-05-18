@extends('_layouts.app')
@section('title','Action Approve - Users')
@section('content')

    <section>
        <h1>Users Approve</h1>
        <div class="bottom-crud-wrapper"><div class="button-wrapper"><x-link text="← Back to Approve Panel" href="{{ route('action-approve.index') }}" class="button-list"/></div></div>
        <table class="content-item-wrapper"><thead><tr><th class="key-content-item-center">№</th><th class="key-content-item-center">Name</th><th class="key-content-item-center">Email</th><th class="key-content-item-center">Actions</th></tr></thead><tbody>
            @forelse($users as $index => $u)
                <tr><td class="key-content-item">{{ $users->firstItem() + $index }}</td><td class="value-content-item">{{ $u->first_name }} {{ $u->second_name }}</td><td class="value-content-item">{{ $u->email }}</td><td class="value-content-item"><x-link text="Detail" class="button-edit" href="{{ route('action-approve.users-show', $u) }}"/></td></tr>
            @empty <tr><td colspan="4" class="value-content-item">No users pending approval.</td></tr> @endforelse
            </tbody></table>
        <div class="pagination">{{ $users->links() }}</div>
    </section>

@endsection

@extends('_layouts.app')
@section('title','Action Approve - Posts')
@section('content')

    <section>
        <h1>Posts Approve</h1>
        <div class="bottom-crud-wrapper"><div class="button-wrapper"><x-link text="← Back to Approve Panel" href="{{ route('action-approve.index') }}" class="button-list"/></div></div>
        <table class="content-item-wrapper"><thead><tr><th class="key-content-item-center">№</th><th class="key-content-item-center">Name</th><th class="key-content-item-center">Email</th><th class="key-content-item-center">Actions</th></tr></thead><tbody>
            @forelse($posts as $index => $p)
                <tr><td class="key-content-item">{{ $posts->firstItem() + $index }}</td><td class="value-content-item">{{ $p->user?->first_name }} {{ $p->user?->second_name }}</td><td class="value-content-item">{{ $p->user?->email }}</td><td class="value-content-item"><x-link text="Detail" class="button-edit" href="{{ route('action-approve.posts-show', $p) }}"/></td></tr>
            @empty <tr><td colspan="4" class="value-content-item">No posts pending approval.</td></tr> @endforelse
            </tbody></table>
        <div class="pagination">{{ $posts->links() }}</div>
    </section>

@endsection

@extends('_layouts.app')

@section('title', 'Posts')

@section('content')
    <section>
        <h1>Company post {{ $company->name }}</h1>

        <div class="content-item-wrapper mt-4">
            <table class="w-full">
                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">User</th>
                    <th class="key-content-item-center">Title</th>
                    <th class="key-content-item-center">Status</th>
                    <th class="key-content-item-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($posts as $index => $post)
                    <tr>
                        <td class="key-content-item">{{ $posts->firstItem() + $index }}</td>
                        <td class="value-content-item">{{ $post->user->first_name ?? '' }} {{ $post->user->second_name ?? '' }}</td>
                        <td class="value-content-item">{{ $post->title }}</td>
                        <td class="value-content-item">{{ $post->status }}</td>
                        <td class="value-content-item">
                            <x-link text="Open" class="button-list" href="{{ route('main.posts.show', [$company, $post]) }}"/>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="value-content-item">No posts</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $posts->withQueryString()->links() }}
        </div>
    </section>
@endsection

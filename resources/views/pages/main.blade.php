@extends('_layouts.app')

@section('title', 'Main')

@section('content')
    <section>
        <h1>{{ __('app.layouts.hc') }} / Main</h1>

        <p class="mt-2 text-sm text-slate-600">
            Relevant companies, posts and users
        </p>

        <div class="content-item-wrapper mt-6">
            <h2 class="text-xl font-semibold">Companies</h2>

            <div class="mt-4 grid gap-4">
                @forelse($companies as $company)
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $company->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $company->city ?? '—' }} · {{ $company->address ?? '—' }}</p>
                            </div>
                            <div class="text-sm text-slate-600">
                                Your role: <span class="font-semibold">{{ $company->pivot->role ?? '—' }}</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm">{{ $company->description ?? 'No description' }}</p>
                        <div class="mt-3 text-sm text-slate-600">
                            Users: <span class="font-semibold">{{ $company->users_count }}</span>
                            · Posts: <span class="font-semibold">{{ $company->posts_count }}</span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <x-link text="Detail" href="{{ route('main.show', $company) }}" class="button-list"/>
                            <x-link text="Posts" href="{{ route('main.posts.index', $company) }}" class="button-edit"/>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No Companies</p>
                @endforelse
            </div>
        </div>

        <div class="content-item-wrapper mt-8">
            <h2 class="text-xl font-semibold">Users</h2>
            <table class="mt-4 w-full">
                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">User</th>
                    <th class="key-content-item-center">Email</th>
                    <th class="key-content-item-center">Company</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $index => $relatedUser)
                    <tr>
                        <td class="key-content-item">{{ $index + 1 }}</td>
                        <td class="value-content-item">{{ $relatedUser->first_name }} {{ $relatedUser->second_name }}</td>
                        <td class="value-content-item">{{ $relatedUser->email }}</td>
                        <td class="value-content-item">
                            {{ $relatedUser->companies->pluck('name')->join(', ') ?: '—' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="value-content-item">No Users</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="content-item-wrapper mt-8">
            <h2 class="text-xl font-semibold">Posts</h2>
            <table class="mt-4 w-full">
                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">Company</th>
                    <th class="key-content-item-center">User</th>
                    <th class="key-content-item-center">Title</th>
                    <th class="key-content-item-center">Status</th>
                    <th class="key-content-item-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($posts as $index => $post)
                    <tr>
                        <td class="key-content-item">{{ $index + 1 }}</td>
                        <td class="value-content-item">{{ $post->company->name ?? '—' }}</td>
                        <td class="value-content-item">{{ $post->user->first_name ?? '' }} {{ $post->user->second_name ?? '' }}</td>
                        <td class="value-content-item">{{ $post->title }}</td>
                        <td class="value-content-item">{{ $post->status }}</td>
                        <td class="value-content-item">
                            @if($post->company)
                                <x-link text="Open" href="{{ route('main.posts.show', [$post->company, $post]) }}" class="button-list"/>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="value-content-item">No posts</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

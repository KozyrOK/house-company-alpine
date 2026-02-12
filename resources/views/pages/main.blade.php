@extends('_layouts.app')

@section('title', 'Main')

@section('content')
    <section>
        <h1>{{ __('app.layouts.hc') }} / Main</h1>

        <p class="section-lead">
            Here are the companies, users, and posts related to your account.
        </p>

        <div class="content-item-wrapper section-spacing">
            <h2 class="section-title">Companies</h2>

            <div class="card-grid">
                @forelse($companies as $company)
                    <div class="card-panel">
                        <div class="card-header">
                            <div>
                                <h3>{{ $company->name }}</h3>
                                <p class="text-muted">{{ $company->city ?? '—' }} · {{ $company->address ?? '—' }}</p>
                            </div>
                            <div class="card-meta">
                                @php($role = $company->pivot->role ?? (auth()->user()->isSuperAdmin() ? 'superadmin' : '—'))
                                Your role: <span class="font-semibold">{{ $role }}</span>
                            </div>
                        </div>
                        <p class="card-description">{{ $company->description ?? 'No description provided.' }}</p>
                        <div class="card-stats">
                            Users: <span class="font-semibold">{{ $company->users_count }}</span>
                            · Posts: <span class="font-semibold">{{ $company->posts_count }}</span>
                        </div>
                        <div class="card-actions">
                            <x-link text="Details" href="{{ route('main.show', $company) }}" class="button-list"/>
                            <x-link text="Posts" href="{{ route('main.posts.index', $company) }}" class="button-edit"/>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No related companies.</p>
                @endforelse
            </div>
        </div>

        <div class="content-item-wrapper section-spacing-lg">
            <h2 class="section-title">Users</h2>
            <table class="table-full">
                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">Name</th>
                    <th class="key-content-item-center">Email</th>
                    <th class="key-content-item-center">Companies</th>
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
                    <tr><td colspan="4" class="value-content-item">No related users.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="content-item-wrapper section-spacing-lg">
            <h2 class="section-title">Posts</h2>
            <table class="table-full">
                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">Company</th>
                    <th class="key-content-item-center">Author</th>
                    <th class="key-content-item-center">Title</th>
                    <th class="key-content-item-center">Status</th>
                    <th class="key-content-item-center">Actions</th>
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
                    <tr><td colspan="6" class="value-content-item">No related posts.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

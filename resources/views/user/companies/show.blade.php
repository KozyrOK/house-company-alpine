@extends('_layouts.app')

@section('title', $company->name)

@section('content')
    <section>
        <h1>{{ $company->name }}</h1>
        <p class="text-sm text-slate-500">{{ $company->city ?? '—' }} · {{ $company->address ?? '—' }}</p>

        <p class="mt-4">{{ $company->description ?? 'No description' }}</p>

        <div class="content-item-wrapper mt-6">
            <h2 class="text-xl font-semibold">Company users</h2>
            <table class="mt-4 w-full">
                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">Name</th>
                    <th class="key-content-item-center">Role</th>
                </tr>
                </thead>
                <tbody>
                @forelse($company->users as $index => $companyUser)
                    <tr>
                        <td class="key-content-item">{{ $index + 1 }}</td>
                        <td class="value-content-item">{{ $companyUser->first_name }} {{ $companyUser->second_name }}</td>
                        <td class="value-content-item">{{ $companyUser->pivot->role ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="value-content-item">No users</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="content-item-wrapper mt-6">
            <h2 class="text-xl font-semibold">Last Posts</h2>
            <table class="mt-4 w-full">
                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">User</th>
                    <th class="key-content-item-center">Title</th>
                    <th class="key-content-item-center">Status</th>
                    <th class="key-content-item-center">Detail</th>
                </tr>
                </thead>
                <tbody>
                @forelse($company->posts as $index => $companyPost)
                    <tr>
                        <td class="key-content-item">{{ $index + 1 }}</td>
                        <td class="value-content-item">{{ $companyPost->user->first_name ?? '' }} {{ $companyPost->user->second_name ?? '' }}</td>
                        <td class="value-content-item">{{ $companyPost->title }}</td>
                        <td class="value-content-item">{{ $companyPost->status }}</td>
                        <td class="value-content-item">
                            <x-link text="Open" class="button-list" href="{{ route('main.posts.show', [$company, $companyPost]) }}"/>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="value-content-item">No posts</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

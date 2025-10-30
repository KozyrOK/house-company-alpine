@extends('_layouts.app')

@section('title', 'Admin Panel')

@section('content')
    <section>
        <h1>{{ __('app.pages.admin_panel') }}</h1>

        <p class="text-gray-600 mb-6">
            Here you can manage users, companies, and posts.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <a href="{{ route('admin.users.index') }}"
               class="block p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                <h2 class="font-semibold mb-1">Users</h2>
                <p class="text-sm text-gray-500">Manage registered users and roles.</p>
            </a>

            <a href="{{ route('admin.companies.index') }}"
               class="block p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                <h2 class="font-semibold mb-1">Companies</h2>
                <p class="text-sm text-gray-500">View and edit company data.</p>
            </a>

            <a href="{{ route('admin.posts.index') }}"
               class="block p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                <h2 class="font-semibold mb-1">Posts</h2>
                <p class="text-sm text-gray-500">Moderate and approve company posts.</p>
            </a>
        </div>
    </section>
@endsection

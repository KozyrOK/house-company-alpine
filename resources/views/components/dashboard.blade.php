@extends('_layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">User Dashboard</h1>

        <p class="text-gray-600 mb-6">
            Welcome back, {{ auth()->user()->first_name }}!
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('companies.index') }}" class="p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                <h2 class="font-semibold">My Companies</h2>
                <p class="text-sm text-gray-500">View or manage your associations.</p>
            </a>

            <a href="{{ route('profile.edit') }}" class="p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                <h2 class="font-semibold">My Profile</h2>
                <p class="text-sm text-gray-500">Edit your personal information.</p>
            </a>
        </div>
    </section>
@endsection

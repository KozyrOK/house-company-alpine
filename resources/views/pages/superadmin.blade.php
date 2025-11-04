@extends('_layouts.app')

@section('title', 'Admin Panel')

@section('content')
    <section>
        <h1>{{ __('app.pages.admin_panel') }}</h1>

        <div class="content-items-box-wrapper">

            <a href="{{ route('admin.users.index') }}"
               class="content-items-box">
                <h2>Users</h2>
                <p>Manage registered users and roles.</p>
            </a>

            <a href="{{ route('admin.companies.index') }}"
               class="content-items-box">
                <h2>Companies</h2>
                <p>View and edit company data.</p>
            </a>

            <a href="{{ route('admin.posts.index') }}"
               class="content-items-box">
                <h2>Posts</h2>
                <p>Moderate and approve company posts.</p>
            </a>

        </div>
    </section>
@endsection

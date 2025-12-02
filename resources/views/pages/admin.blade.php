@extends('_layouts.app')

@section('title', 'Admin Panel')

@section('content')

    <section>

        <h1>{{ __('app.pages.admin_panel') }}</h1>

        <div class="admin-panel-content-box-wrapper">

            <a href="{{ route('admin.users.index') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.user/>
                    <h2>Users</h2>
                    <p>Manage registered users and roles</p>
                </div>
            </a>

            <a href="{{ route('admin.companies.index') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.company/>
                    <h2>Companies</h2>
                    <p>View and edit company data</p>
                </div>
            </a>

            <a href="{{ route('admin.posts.index') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.post/>
                    <h2>Posts</h2>
                    <p>Moderate and approve company posts</p>
                </div>
            </a>

        </div>

    </section>

@endsection

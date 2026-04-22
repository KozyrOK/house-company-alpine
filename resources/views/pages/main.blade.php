@extends('_layouts.app')

@section('title', 'Main Panel')

@section('content')

    <section>

        <h1>User Panel</h1>

        <div class="admin-panel-content-box-wrapper">

            @if($showUsersCard)
                <a href="{{ route('main.users.index', $company) }}"
                   class="content-items-box">
                    <div class="content-item-box-wrapper">
                        <x-icons.user/>
                        <h2>Users</h2>
                        <p>Users in current company</p>
                    </div>
                </a>
            @endif

            @if($showPostsCard)
                    <a href="{{ route('main.posts.index', $company) }}"
                   class="content-items-box">
                    <div class="content-item-box-wrapper">
                        <x-icons.post/>
                        <h2>Posts</h2>
                        <p>Posts in current company</p>
                    </div>
                </a>
            @endif

        </div>

    </section>

@endsection

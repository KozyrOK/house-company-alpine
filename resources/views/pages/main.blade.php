@extends('_layouts.app')

@section('title', 'Main Panel')

@section('content')

    <section>

        <h1>{{__('app.users.user_panel')}}</h1>

        <div class="admin-panel-content-box-wrapper">

            @if($showUsersCard)
                <a href="{{ route('main.users.index') }}"
                   class="content-items-box">
                    <div class="content-item-box-wrapper">
                        <x-icons.user/>
                        <h2>{{__('app.users.users')}}</h2>
                        <p>{{__('app.users.users_in_current_company')}}</p>
                    </div>
                </a>
            @endif

            @if($showPostsCard)
                    <a href="{{ route('main.posts.index') }}"
                   class="content-items-box">
                    <div class="content-item-box-wrapper">
                        <x-icons.post/>
                        <h2>{{__('app.posts.posts')}}</h2>
                        <p>{{__('app.posts.posts_in_current_company')}}</p>
                    </div>
                </a>
            @endif

        </div>

    </section>

@endsection

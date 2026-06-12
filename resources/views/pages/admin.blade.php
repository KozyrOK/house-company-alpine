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
                    <h2>{{__('app.users.user')}}</h2>
                    <p>{{__('app.users.user_box_description')}}</p>
                </div>
            </a>

            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.companies.index') }}"
                   class="content-items-box">
                    <div class="content-item-box-wrapper">
                        <x-icons.company/>
                        <h2>{{__('app.companies.companies')}}</h2>
                        <p>{{__('app.companies.company_box_description')}}</p>
                    </div>
                </a>
            @endif

            <a href="{{ route('admin.posts.index') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.post/>
                    <h2>{{__('app.posts.posts')}}</h2>
                    <p>{{__('app.posts.posts_box_description')}}</p>
                </div>
            </a>

        </div>

    </section>

@endsection

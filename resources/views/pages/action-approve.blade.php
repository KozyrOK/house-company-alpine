@extends('_layouts.app')

@section('title', 'Action Approve Panel')

@section('content')

    <section>

        <h1>{{ __('app.pages.action_aprrove_panel') }}</h1>

        <div class="admin-panel-content-box-wrapper">

            <a href="{{ route('action-approve.users-approve') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.user/>
                    <h2>Users Approve</h2>
                </div>
            </a>

            <a href="{{ route('action-approve.posts-approve') }}"
               class="content-items-box">
                <div class="content-item-box-wrapper">
                    <x-icons.post/>
                    <h2>Post Approve</h2>
                </div>
            </a>
        </div>

    </section>

@endsection

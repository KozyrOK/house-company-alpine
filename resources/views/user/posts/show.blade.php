@extends('_layouts.app')

@section('title', 'Main - Post Detail')

@section('content')

    <section>

        <h1>{{__('app.posts.post')}}</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="app.buttons.back_to_list" href="{{ route('main.posts.index') }}" class="button-list"/>
                </div>

                <div></div>

                <div class="button-wrapper">
                    <x-link text="app.buttons.main_menu" href="{{ route('main.index') }}" class="button-list"/>
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $post->id }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.title')}}</th><td class="value-content-item">{{ $post->title }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.company')}}</th><td class="value-content-item">{{ $post->company?->name ?? '-' }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.status')}}</th><td class="value-content-item">{{ $post->status }}</td></tr>
                <tr><th class="key-content-item">{{__('app.tables.content')}}</th><td class="value-content-item">{{ $post->content }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

            <div class="button-wrapper">
                @can('update', $post)
                    <x-link text="app.buttons.edit_post" href="{{ route('main.posts.edit', $post) }}" class="button-edit"/>
                @endcan
            </div>

            <div></div>

            </div>

        </div>

    </section>

@endsection

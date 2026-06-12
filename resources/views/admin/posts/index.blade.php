@extends('_layouts.app')

@section('title','Admin - Posts')

@section('content')

    <section>

        <h1>{{__('app.posts.posts')}}</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_admin_panel" href="{{ route('admin.index') }}" class="button-list"/>
            </div>

            <div class="button-wrapper">
                <x-link text="app.buttons.trash" href="{{ route('admin.posts.trash') }}" class="button-trash"/>
            </div>

            <div class="button-wrapper">
                <x-link text="app.buttons.create_post" href="{{ route('admin.posts.create') }}" class="button-edit"/>
            </div>

        </div>

        <x-filter.filterPost/>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.title')}}</th>
                <th class="key-content-item-center">{{__('app.tables.company')}}</th>
                <th class="key-content-item-center">{{__('app.tables.author')}}</th>
                <th class="key-content-item-center">{{__('app.tables.status')}}</th>
                <th class="key-content-item-center">{{__('app.tables.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($posts as $index => $p)
                <tr>
                    <td class="key-content-item">{{ $posts->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $p->title }}</td>
                    <td class="value-content-item">{{ $p->company?->name ?? '-' }}</td>
                    <td class="value-content-item">{{ trim(($p->user?->first_name ?? '').' '.($p->user?->second_name ?? '')) ?: '-' }}</td>
                    <td class="value-content-item">{{ $p->status }}</td>
                    <td class="value-content-item"><x-link text="app.buttons.detail" class="button-list" href="{{ route('admin.posts.show', $p) }}"/></td>
                </tr>
            @empty
                <tr><td colspan="6" class="value-content-item">{{__('app.posts.no_posts_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="pagination-list">{{ $posts->links() }}</div>

    </section>

@endsection

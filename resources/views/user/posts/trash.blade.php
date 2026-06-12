@extends('_layouts.app')

@section('title','Deleted Posts')

@section('content')
    <section>
        <h1>{{__('app.posts.edit_post')}}</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_posts" href="{{ route('main.posts.index') }}" class="button-list"/>
            </div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">{{__('app.tables.title')}}</th>
                <th class="key-content-item-center">{{__('app.tables.company')}}</th>
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
                    <td class="value-content-item">{{ $p->status }}</td>
                    <td class="value-content-item">
                        @can('restore', $p)
                            <form method="POST" action="{{ route('main.posts.restore', $p->id) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="app.buttons.restore" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="value-content-item">{{__('app.posts.no_deleted_posts_found')}}</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
@endsection

@extends('_layouts.app')

@section('title', 'Edit Post')

@section('content')

    <section class="content-item-wrapper">

        <h1>{{__('app.posts.edit_post')}}</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_detail" href="{{ route('main.posts.show', $post) }}" class="button-list"/>
            </div>

            <div></div>

            <div class="button-wrapper">
                <x-link text="app.buttons.main_menu" href="{{ route('main.index') }}" class="button-list"/>
            </div>

        </div>

        <form method="POST" action="{{ route('main.posts.update', $post) }}">
            @csrf
            @method('PATCH')

            <table class="w-full">
                <tr>
                    <th class="key-content-item">{{__('app.companies.company')}}</th>
                    <td class="value-content-item">
                        <select name="company_id" class="input-field" disabled>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id', $post->company_id) == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr><th class="key-content-item">{{__('app.tables.title')}}</th><td class="value-content-item"><input type="text" name="title" value="{{ old('title', $post->title) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.content')}}</th><td class="value-content-item"><textarea name="content" class="input-field" rows="6">{{ old('content', $post->content) }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">{{__('app.tables.status')}}</th>
                    <td class="value-content-item">
                        <select name="status" class="input-field">
                            @foreach(['draft','future','pending'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $post->status) === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    <x-button text="app.buttons.save" type="submit" class="button-edit"/>
                </div>

                <div></div>

                <div class="button-wrapper">
                    <x-link text="app.buttons.cancel" href="{{ route('main.posts.show', $post) }}" class="button-delete"/>
                </div>

            </div>

        </form>

    </section>

@endsection

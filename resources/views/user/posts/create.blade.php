@extends('_layouts.app')

@section('title', 'Create Post')

@section('content')

    <section class="content-item-wrapper">

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_list" href="{{ route('main.posts.index') }}" class="button-list"/>
            </div>

            <div></div>

            <div class="button-wrapper">
                <x-link text="app.buttons.main_menu" href="{{ route('main.index') }}" class="button-list"/>
            </div>

        </div>

        <form action="{{ route('main.posts.store') }}" method="POST">
            @csrf
            <table class="w-full">
                <tr>
                    <th class="key-content-item">{{__('app.tables.company')}}</th>
                    <td class="value-content-item">
                        <select name="company_id" class="input-field" required disabled>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr><th class="key-content-item">{{__('app.tables.title')}}</th><td class="value-content-item"><input type="text" name="title" class="input-field" value="{{ old('title') }}" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.content')}}</th><td class="value-content-item"><textarea name="content" rows="6" class="input-field" required>{{ old('content') }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">{{__('app.tables.status')}}</th>
                    <td class="value-content-item">
                        <select name="status" class="input-field">
                            @foreach(['draft','future','pending'] as $status)
                                <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    <x-button text="app.buttons.create_post" type="submit" class="button-edit"/>
                </div>

            </div>

        </form>

    </section>

@endsection

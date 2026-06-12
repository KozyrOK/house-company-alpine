@extends('_layouts.app')

@section('title', 'Admin – Edit User')

@section('content')

    <section class="content-item-wrapper">

        <h1>{{__('app.users.edit_user')}}</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_detail" href="{{ route('admin.users.show', $user) }}" class="button-list"/>
            </div>

            <div>
                <img class="company-image" src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-image-company.webp') }}" alt="user image">
            </div>

            <div class="button-wrapper">
                <x-link text="app.buttons.admin_menu" href="{{ route('admin.index') }}" class="button-list"/>
            </div>

        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PATCH')

            <table class="w-full">
                <tr><th class="key-content-item">{{__('app.tables.first_name')}}</th><td class="value-content-item"><input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.second_name')}}</th><td class="value-content-item"><input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.e_mail')}}</th><td class="value-content-item"><input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.phone')}}</th><td class="value-content-item"><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field"></td></tr>
                <tr>
                    <th class="key-content-item">{{__('app.tables.account_status')}}</th>
                    <td class="value-content-item">
                        <select name="status_account" class="input-field">
                            @foreach(['pending' => 'Pending', 'active' => 'Active', 'deleted' => 'Deleted'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status_account', $user->status_account) === $value)>{{ $label }}</option>
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
                    <x-link text="app.buttons.cancel" href="{{ route('admin.users.show', $user) }}" class="button-delete"/>
                </div>

            </div>

        </form>

    </section>

@endsection

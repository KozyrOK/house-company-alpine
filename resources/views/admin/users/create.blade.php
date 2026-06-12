@extends('_layouts.app')

@section('title','Admin - Create User')

@section('content')

    <section class="content-item-wrapper" x-data="{ preview: null, updatePreview(e){ const f=e.target.files[0]; this.preview = f ? URL.createObjectURL(f) : null; } }">

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_list" href="{{ route('admin.users.index') }}" class="button-list"/>
            </div>

            <div>
                <img alt="user preview" class="company-image" :src="preview || '{{ asset('images/default-image-company.webp') }}'">
            </div>

            <div class="button-wrapper">
                <x-link text="app.buttons.admin_menu" href="{{ route('admin.index') }}" class="button-list"/>
            </div>

        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <table class="w-full">
                <tr><th class="key-content-item">{{__('app.tables.company')}}</th><td class="value-content-item"><select name="company_id" class="input-field" required>@foreach($companies as $company)<option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>@endforeach</select></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.role')}}</th><td class="value-content-item"><select name="role" class="input-field" required>@foreach(['user','company_head','admin'] as $role)<option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>@endforeach</select></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.first_name')}}</th><td class="value-content-item"><input type="text" name="first_name" class="input-field" value="{{ old('first_name') }}" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.second_name')}}</th><td class="value-content-item"><input type="text" name="second_name" class="input-field" value="{{ old('second_name') }}" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.e_mail')}}</th><td class="value-content-item"><input type="email" name="email" class="input-field" value="{{ old('email') }}" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.password')}}</th><td class="value-content-item"><input type="password" name="password" class="input-field" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.confirm_password')}}</th><td class="value-content-item"><input type="password" name="password_confirmation" class="input-field" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.phone')}}</th><td class="value-content-item"><input type="text" name="phone" class="input-field" value="{{ old('phone') }}"></td></tr>
                <tr>
                    <th class="key-content-item">{{__('app.tables.user_image')}}</th>
                    <td class="value-content-item">
                        <input type="file" name="image" accept="image/*" @@change="updatePreview">
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    <x-link text="app.buttons.cancel" href="{{ route('admin.users.index') }}" class="button-list"/>
                </div>

                <div class="button-wrapper">
                    <x-button text="app.buttons.create_user" type="submit" class="button-edit"/>
                </div>

            </div>

        </form>

    </section>

@endsection

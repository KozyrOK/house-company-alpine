@extends('_layouts.app')

@section('title', 'Admin - Create Company')

@section('content')

    <section class="content-item-wrapper" x-data="{ preview: null, updatePreview(event){ const file = event.target.files[0]; this.preview = file ? URL.createObjectURL(file) : null; } }">

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back_to_list" href="{{ route('admin.companies.index') }}" class="button-list"/>
            </div>

            <div>
                <img alt="logo preview" :src="preview ?? '/images/default-image-company.webp'" class="company-image">
            </div>

            <div class="button-wrapper">
                <x-link text="app.buttons.admin_menu" href="{{ route('admin.index') }}" class="button-list"/>
            </div>

        </div>

        <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <table class="w-full">
                <tr><th class="key-content-item">{{__('app.tables.name')}}</th><td class="value-content-item"><input type="text" name="name" class="input-field" value="{{ old('name') }}" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.address')}}</th><td class="value-content-item"><input type="text" name="address" class="input-field" value="{{ old('address') }}"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.city')}}</th><td class="value-content-item"><input type="text" name="city" class="input-field" value="{{ old('city') }}"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.description')}}</th><td class="value-content-item"><textarea name="description" class="input-field" rows="4">{{ old('description') }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">{{__('app.tables.company_logo')}}</th>
                    <td class="value-content-item" colspan="2">
                        <input type="file" name="logo" accept="image/*" @@change="updatePreview">
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    <x-button text="app.buttons.create_company" type="submit" class="button-edit"/>
                </div>

            </div>

        </form>

    </section>

@endsection

@extends('_layouts.app')

@section('title', 'Admin – Edit Company')

@section('content')

    <section class="content-item-wrapper" x-data="{ preview: null, updatePreview(event){ const file = event.target.files[0]; this.preview = file ? URL.createObjectURL(file) : null; } }">

        <h1>{{__('app.companies.edit_company')}}</h1>

        @php($backRoute = auth()->user()->isSuperAdmin() ? route('admin.companies.show', $company) : route('company.current'))

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="app.buttons.back" href="{{ $backRoute }}" class="button-list"/>
            </div>

            <div>
                <img alt="logo" class="company-image" :src="preview || '{{ route('admin.companies.logo', $company) }}'">
            </div>

            <div class="button-wrapper">
                @if(auth()->user()->isSuperAdmin())
                    <x-link text="app.buttons.admin_menu" href="{{ route('admin.index') }}" class="button-list"/>
                @endif
            </div>

        </div>

        <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <table class="w-full">
                <tr><th class="key-content-item">{{__('app.tables.name')}}</th><td class="value-content-item"><input type="text" name="name" class="input-field" value="{{ old('name', $company->name) }}" required></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.address')}}</th><td class="value-content-item"><input type="text" name="address" class="input-field" value="{{ old('address', $company->address) }}"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.city')}}</th><td class="value-content-item"><input type="text" name="city" class="input-field" value="{{ old('city', $company->city) }}"></td></tr>
                <tr><th class="key-content-item">{{__('app.tables.description')}}</th><td class="value-content-item"><textarea name="description" class="input-field">{{ old('description', $company->description) }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">{{__('app.tables.company_logo')}}</th>
                    <td colspan="2" class="value-content-item">
                        <input type="file" name="logo" accept="image/*" @@change="updatePreview">
                        <label class="ml-2"><input type="checkbox" name="remove_logo" value="1">{{__('app.companies.remove_current_logo')}}</label>
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    <x-button text="app.buttons.save" type="submit" class="button-edit"/>
                </div>

                <div></div>

                <div class="button-wrapper">
                    <x-link text="app.buttons.cancel" href="{{ $backRoute }}" class="button-delete"/>
                </div>

            </div>

        </form>

    </section>

@endsection

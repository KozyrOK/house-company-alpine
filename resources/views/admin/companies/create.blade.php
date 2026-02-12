@extends('_layouts.app')

@section('title', 'Admin - Create Company')

@section('content')
    <section class="content-item-wrapper" x-data="{ preview: null, updatePreview(event){ const file = event.target.files[0]; this.preview = file ? URL.createObjectURL(file) : null; } }">
        <div class="top-crud-wrapper">
            <div class="button-wrapper"><x-link text="← Back to list" href="{{ route('admin.companies.index') }}" class="button-list"/></div>
            <div><img alt="logo preview" :src="preview ?? '/images/default-image-company.jpg'" class="company-image"></div>
            <div class="button-wrapper"><x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/></div>
        </div>

        <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <table>
                <tr><th class="key-content-item">Name</th><td class="value-content-item" colspan="2"><input type="text" name="name" class="input-field" value="{{ old('name') }}" required></td></tr>
                <tr><th class="key-content-item">Address</th><td class="value-content-item" colspan="2"><input type="text" name="address" class="input-field" value="{{ old('address') }}"></td></tr>
                <tr><th class="key-content-item">City</th><td class="value-content-item" colspan="2"><input type="text" name="city" class="input-field" value="{{ old('city') }}"></td></tr>
                <tr><th class="key-content-item">Description</th><td class="value-content-item" colspan="2"><textarea name="description" class="input-field" rows="4">{{ old('description') }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">Company Logo</th>
                    <td class="value-content-item" colspan="2">
                        <input type="file" name="logo" accept="image/*" @change="updatePreview">
                    </td>
                </tr>
            </table>
            <div class="bottom-crud-wrapper"><div class="action-row-end"><x-button text="Create Company" type="submit" class="button-edit"/></div></div>
        </form>
    </section>
@endsection

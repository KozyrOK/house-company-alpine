@extends('_layouts.app')

@section('title', 'Admin – Edit Company')

@section('content')

    <section class="content-item-wrapper" x-data="{ preview: null, updatePreview(event){ const file = event.target.files[0]; this.preview = file ? URL.createObjectURL(file) : null; } }">

        <h1>Edit Company</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="← Back to detail" href="{{ route('admin.companies.show', $company) }}" class="button-list"/>
            </div>

            <div>
                <img alt="logo" class="company-image" :src="preview || '{{ route('admin.companies.logo', $company) }}'">
            </div>

            <div class="button-wrapper">
                <x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/>
            </div>

        </div>

        <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <table>
                <tr><th class="key-content-item">Name</th><td colspan="2" class="value-content-item"><input type="text" name="name" class="input-field" value="{{ old('name', $company->name) }}" required></td></tr>
                <tr><th class="key-content-item">Address</th><td colspan="2" class="value-content-item"><input type="text" name="address" class="input-field" value="{{ old('address', $company->address) }}"></td></tr>
                <tr><th class="key-content-item">City</th><td colspan="2" class="value-content-item"><input type="text" name="city" class="input-field" value="{{ old('city', $company->city) }}"></td></tr>
                <tr><th class="key-content-item">Description</th><td colspan="2" class="value-content-item"><textarea name="description" class="input-field">{{ old('description', $company->description) }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">Company Logo</th>
                    <td colspan="2" class="value-content-item">
                        <input type="file" name="logo" accept="image/*" @@change="updatePreview">
                        <label class="ml-2"><input type="checkbox" name="remove_logo" value="1"> Remove current logo</label>
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    <x-button text="Save" type="submit" class="button-edit"/>
                </div>

                <div class="button-wrapper">
                    <x-link text="Cancel" href="{{ route('admin.companies.show', $company) }}" class="button-delete"/>
                </div>

            </div>

        </form>

    </section>

@endsection

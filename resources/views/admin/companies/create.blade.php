@extends('_layouts.app')

@section('title', 'Admin - Create Company')

@section('content')

    <div
        x-data="{
            preview: null,
            updatePreview(event) {
                const file = event.target.files[0];

                if (!file) {
                    this.preview = null;
                    return;
                }

                const reader = new FileReader();
                reader.onload = e => this.preview = e.target.result;
                reader.readAsDataURL(file);
            }
        }"
        class="content-item-wrapper"
    >

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-button
                    text="← Back to list"
                    href="{{ route('admin.companies.index') }}"
                    class="button-list"
                />
            </div>

            <div>
                <img
                    alt="logo preview"
                    :src="preview ?? '/images/default-image-company.jpg'"
                    class="company-image"
                >
            </div>

            <div class="button-wrapper">
                <x-button
                    text="Admin Menu"
                    href="{{ route('admin.index') }}"
                    class="button-list"
                />
            </div>

        </div>

        <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <table>

                <tr>
                    <th class="key-content-item">Name</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="name" class="form-input" required>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Address</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="address" class="form-input">
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">City</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="city" class="form-input">
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Description</th>
                    <td class="value-content-item" colspan="2">
                        <textarea name="description" class="form-input" rows="4"></textarea>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Company Logo</th>
                    <td class="value-content-item" colspan="2">
                        <input
                            type="file"
                            name="logo"
                            accept="image/*"
                            @@change="updatePreview"
                        >
                    </td>
                </tr>

            </table>

            <div class="bottom-crud-wrapper">
                <div class="flex justify-end">
                    <x-button
                        text="Create Company"
                        type="submit"
                        class="button-edit"
                    />
                </div>
            </div>

        </form>

    </div>

@endsection

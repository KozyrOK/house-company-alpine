@extends('_layouts.app')

@section('title', 'Admin - Create Post')

@section('content')

    <div
        x-data="{
        preview: null,
        updatePreview(e) {
            const file = e.target.files[0];
            if (!file) {
                this.preview = null;
                return;
            }
            const reader = new FileReader();
            reader.onload = ev => this.preview = ev.target.result;
            reader.readAsDataURL(file);
        }
    }"
        class="content-item-wrapper"
    >

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link
                    text="← Back to list"
                    href="{{ route('admin.posts.index') }}"
                    class="button-list"
                />
            </div>

            <div>
                <img
                    alt="post image preview"
                    :src="preview ?? '/images/default-image-company.jpg'"
                    class="company-image"
                >
            </div>

            <div class="button-wrapper">
                <x-link
                    text="Admin Menu"
                    href="{{ route('admin.index') }}"
                    class="button-list"
                />
            </div>

        </div>

        <form
            action="{{ route('admin.posts.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <table>

                <tr>
                    <th class="key-content-item">Company</th>
                    <td colspan="2" class="value-content-item">
                        <select name="company_id" class="input-field" required>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Title</th>
                    <td colspan="2" class="value-content-item">
                        <input
                            type="text"
                            name="title"
                            class="input-field"
                            required
                        >
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Content</th>
                    <td colspan="2" class="value-content-item">
                    <textarea
                        name="content"
                        rows="6"
                        class="input-field"
                        required
                    ></textarea>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Status</th>
                    <td colspan="2" class="value-content-item">
                        <select name="status" class="input-field">
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="publish">Publish</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Image</th>
                    <td colspan="2" class="value-content-item">
                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                            @change="updatePreview"
                        >
                    </td>
                </tr>

            </table>

            <div class="bottom-crud-wrapper">
                <div class="flex justify-end">
                    <x-button
                        text="Create Post"
                        type="submit"
                        class="button-edit"
                    />
                </div>
            </div>

        </form>

    </div>

@endsection

@extends('_layouts.app')

@section('title', 'Admin – Edit Company')

@section('content')

    <div x-data="adminEditCompany({{ $companyId }})" x-init="init()">

        <h1>Edit Company</h1>

        <div x-show="loading">Loading...</div>

        <div x-show="!loading">

            <div class="content-item-wrapper">

                <div class="top-crud-wrapper">

                    <div class="button-wrapper">
                        <x-button
                            text="← Back to detail"
                            href=":`/admin/companies/${company.id}`"
                            class="button-list"
                        />
                    </div>

                    <div>
                        <img
                            alt="logo"
                            x-ref="logoPreview"
                            :src="previewUrl || (company.logo_path
                            ? '/admin/companies/' + company.id + '/logo'
                            : '/images/default-image-company.jpg')"
                        @@error="event.target.src='/images/default-image-company.jpg'"
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

                <form @@submit.prevent="save" class="space-y-6 mt-6">

                    <table>

                        <tr>
                            <th class="key-content-item">Name</th>
                            <td colspan="2" class="value-content-item">
                                <input type="text" x-model="form.name" class="input-field" />
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Address</th>
                            <td colspan="2" class="value-content-item">
                                <input type="text" x-model="form.address" class="input-field" />
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">City</th>
                            <td colspan="2" class="value-content-item">
                                <input type="text" x-model="form.city" class="input-field" />
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Description</th>
                            <td colspan="2" class="value-content-item">
                                <textarea x-model="form.description" class="input-field"></textarea>
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Company Logo</th>
                            <td colspan="2" class="value-content-item">

                                <input type="file" accept="image/*" @change="handleFileUpload">

                                <div class="mt-2 flex space-x-4">

                                    <button
                                        type="button"
                                        class="button-edit"
                                        @@click="removeLogo()"
                                    >
                                        Remove Logo
                                    </button>

                                    <template x-if="previewUrl">
                                        <button
                                            type="button"
                                            class="button-edit"
                                            @@click="clearPreview()"
                                        >
                                            Undo Preview
                                        </button>
                                    </template>
                                </div>

                            </td>
                        </tr>

                    </table>

                    <div class="bottom-crud-wrapper">

                        <div class="flex justify-start">
                            <x-button
                                text="Save"
                                type="submit"
                                class="button-edit"
                            />
                        </div>

                        <div class="flex justify-end">
                            <x-button
                                text="Cancel"
                                href=":`/admin/companies/${company.id}`"
                                class="button-delete"
                            />
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

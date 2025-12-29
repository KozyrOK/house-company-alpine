@extends('_layouts.app')

@section('title', 'Admin – Edit User')

@section('content')

    <div x-data="adminEditUser({{ $userId }})" x-init="init()">

        <h1>Edit User</h1>

        <div x-show="loading">Loading...</div>

        <div x-show="!loading">

            <div class="content-item-wrapper">

                <div class="top-crud-wrapper">

                    <div class="button-wrapper">
                        <x-link
                            text="← Back to detail"
                            x-bind:href="`/admin/users/${userId}`"
                            class="button-list"
                        />
                    </div>

                    <div>
                        <img
                            class="company-image"
                            :src="previewUrl || user.image_path || '/images/default-image-company.jpg'"
                        @error="event.target.src='/images/default-image-company.jpg'"
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

                <form @submit.prevent="save">

                    <table>

                        <tr>
                            <th class="key-content-item">First name</th>
                            <td colspan="2" class="value-content-item">
                                <input type="text" x-model="form.first_name" class="input-field">
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Second name</th>
                            <td colspan="2" class="value-content-item">
                                <input type="text" x-model="form.second_name" class="input-field">
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Email</th>
                            <td colspan="2" class="value-content-item">
                                <input type="email" x-model="form.email" class="input-field">
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Phone</th>
                            <td colspan="2" class="value-content-item">
                                <input type="text" x-model="form.phone" class="input-field">
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Account status</th>
                            <td colspan="2" class="value-content-item">
                                <select x-model="form.status_account" class="input-field">
                                    <option value="pending">Pending</option>
                                    <option value="active">Active</option>
                                    <option value="blocked">Blocked</option>
                                </select>
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
                            <x-link
                                text="Cancel"
                                x-bind:href="`/admin/users/${userId}`"
                                class="button-delete"
                            />
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

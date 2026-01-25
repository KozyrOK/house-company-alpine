@extends('_layouts.app')

@section('title', 'Admin - User Detail')

@section('content')

    <div x-data="adminEditUser({{ $userId }})">

        <h1>User</h1>

        <div x-show="loading">Loading...</div>

        <div x-show="!loading">

            <div class="content-item-wrapper">

                <div class="top-crud-wrapper">

                    <div class="button-wrapper">
                        <x-link
                            text="← Back to list"
                            href="{{ route('admin.users.index') }}"
                            class="button-list"
                        />
                    </div>

                    <div>
                        <img
                            class="company-image"
                            :src="user.image_path ?? '/images/default-image-company.jpg'"
                        @@error="event.target.src='/images/default-image-company.jpg'"
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

                <table>

                    <tr>
                        <th class="key-content-item">ID</th>
                        <td colspan="2" class="value-content-item" x-text="user.id"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Name</th>
                        <td colspan="2" class="value-content-item"
                            x-text="user.first_name + ' ' + user.second_name">
                        </td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Email</th>
                        <td colspan="2" class="value-content-item" x-text="user.email"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Phone</th>
                        <td colspan="2" class="value-content-item" x-text="user.phone"></td>
                    </tr>

                </table>

                <div class="bottom-crud-wrapper">

                    <div class="flex justify-start">
                        <x-link
                            text="Edit User"
                            x-bind:href="`/admin/users/${user.id}/edit`"
                            class="button-edit"
                        />
                    </div>

                    <div class="flex justify-end">
                        <x-link
                            text="Delete User"
                            x-bind:href="`/admin/users/${user.id}/delete`"
                            class="button-delete"
                        />
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

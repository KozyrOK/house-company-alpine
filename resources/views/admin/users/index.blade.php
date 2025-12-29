@extends('_layouts.app')

@section('title','Admin - Users')

@section('content')

    <div x-data="adminUsersList()" x-init="fetchUsers()">

        <h1>Users</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link
                    text="← Back to Admin Panel"
                    href="{{ route('admin.index') }}"
                    class="button-list"
                />
            </div>

            <div></div>

            <div class="button-wrapper">
                <x-link
                    text="Create new user"
                    href="{{ route('admin.users.create') }}"
                    class="button-edit"
                />
            </div>

        </div>

        <div x-show="loading">Loading...</div>

        <div x-show="!loading">


            <table class="content-item-wrapper">

                <thead>
                <tr>
                    <th class="key-content-item-center">#</th>
                    <th class="key-content-item-center">Name</th>
                    <th class="key-content-item-center">Email</th>
                    <th class="key-content-item-center p-2">Actions</th>
                </tr>
                </thead>

                <tbody>

                <template x-for="(u, index) in users" :key="u.id">

                    <tr>
                        <td class="key-content-item" x-text="index + 1"></td>
                        <td class="value-content-item" x-text="u.first_name + ' ' + u.second_name"></td>
                        <td class="value-content-item" x-text="u.email"></td>

                        <td class="value-content-item">
                            <x-link
                                text="Detail"
                                class="button-list"
                                x-bind:href="`/admin/users/${u.id}`"
                            />
                        </td>
                    </tr>

                </template>

                </tbody>

            </table>
        </div>

    </div>

@endsection

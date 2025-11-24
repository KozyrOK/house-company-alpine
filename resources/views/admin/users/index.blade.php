@extends('_layouts.app')

@section('title','Admin - Users')

@section('content')
    <div x-data="adminUsersList()" x-init="fetchUsers()">
        <h1>Users</h1>

        <div x-show="loading">Loading...</div>

        <table x-show="!loading">
            <thead>
            <tr>
                <th class="content-cell-center">#</th>
                <th class="content-cell-center">Name</th>
                <th class="content-cell-center">Email</th>
                <th class="content-cell-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <template x-for="(u, index) in users" :key="u.id">
                <tr>
                    <td class="content-cell-center" x-text="index + 1"></td>
                    <td class="content-cell" x-text="u.first_name + ' ' + u.second_name"></td>
                    <td class="content-cell" x-text="u.email"></td>

                    <td class="content-cell-center">
                        <x-button
                            text="Edit"
                            :href="'/superadmin/users/' + u.id"
                            class="button-list"
                        />
                        <x-button
                            text="Delete"
                            class="button-list bg-red-600"
                        />
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
@endsection

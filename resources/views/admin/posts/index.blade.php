@extends('_layouts.app')

@section('title','Admin - Posts')

@section('content')
    <div x-data="adminPostsList()" x-init="fetchPosts()">

        <h1>Posts</h1>

        <div x-show="loading">Loading...</div>

        <table x-show="!loading">
            <thead>
            <tr>
                <th class="content-cell-center">#</th>
                <th class="content-cell-center">Title</th>
                <th class="content-cell-center">Company</th>
                <th class="content-cell-center">Status</th>
                <th class="content-cell-center">Actions</th>
            </tr>
            </thead>

            <tbody>
            <template x-for="(p, index) in posts" :key="p.id">
                <tr>
                    <td class="content-cell-center" x-text="index + 1"></td>
                    <td class="content-cell" x-text="p.title"></td>
                    <td class="content-cell" x-text="p.company?.name ?? '-'"></td>
                    <td class="content-cell-center" x-text="p.status"></td>

                    <td class="content-cell-center">
                        <x-button
                            text="Edit"
                            :href="'/superadmin/posts/' + p.id"
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

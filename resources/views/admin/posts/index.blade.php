@extends('_layouts.app')

@section('title','Admin - Posts')

@section('content')

    <div x-data="adminPostsList()" x-init="fetchPosts()">

        <h1>Posts</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-button
                    text="← Back to Admin Panel"
                    href="{{ route('admin.index') }}"
                    class="button-list"
                />
            </div>

            <div></div>

            <div class="button-wrapper">
                <x-button
                    text="Create Post"
                    href="{{ route('admin.posts.create') }}"
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
                    <th class="key-content-item-center">Title</th>
                    <th class="key-content-item-center">Company</th>
                    <th class="key-content-item-center">Status</th>
                    <th class="key-content-item-center p-2">Actions</th>
                </tr>
                </thead>

                <tbody>

                <template x-for="(p, index) in posts" :key="p.id">

                    <tr>
                        <td class="key-content-item" x-text="index + 1"></td>
                        <td class="value-content-item" x-text="p.title"></td>
                        <td class="value-content-item" x-text="p.company?.name ?? '-'"></td>
                        <td class="value-content-item" x-text="p.status"></td>

                        <td class="value-content-item">
                            <x-link
                                text="Detail"
                                class="button-list"
                                x-bind:href="`/admin/posts/${p.id}`"
                            />
                        </td>
                    </tr>

                </template>

                </tbody>

            </table>
        </div>

    </div>

@endsection

@extends('_layouts.app')

@section('title', 'Admin - Post Detail')

@section('content')

    <div x-data="showPost({{ $postId }})" x-init="fetchPost()">

        <h1>Post</h1>

        <div x-show="loading">Loading...</div>

        <div x-show="!loading">

            <div class="content-item-wrapper">

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
                            class="company-image"
                            :src="post.image_path
                            ? `/admin/posts/${post.id}/image`
                            : '/images/default-image-company.jpg'"
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

                <table>

                    <tr>
                        <th class="key-content-item">ID</th>
                        <td colspan="2" class="value-content-item" x-text="post.id"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Title</th>
                        <td colspan="2" class="value-content-item" x-text="post.title"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Company</th>
                        <td colspan="2" class="value-content-item" x-text="post.company?.name"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Status</th>
                        <td colspan="2" class="value-content-item" x-text="post.status"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Content</th>
                        <td colspan="2" class="value-content-item" x-text="post.content"></td>
                    </tr>

                </table>

                <div class="bottom-crud-wrapper">

                    <div class="flex justify-start">
                        <x-link
                            text="Edit Post"
                            x-bind:href="`/admin/posts/${post.id}/edit`"
                            class="button-edit"
                        />
                    </div>

                    <div class="flex justify-end">
                        <x-link
                            text="Delete Post"
                            x-bind:href="`/admin/posts/${post.id}/delete`"
                            class="button-delete"
                        />
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

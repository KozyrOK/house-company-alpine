@extends('_layouts.app')

@section('title', 'Admin – Edit Post')

@section('content')

    <div x-data="adminEditPost({{ $postId }})" x-init="init()">

        <h1>Edit Post</h1>

        <div x-show="loading">Loading...</div>

        <div x-show="!loading">

            <div class="content-item-wrapper">

                <div class="top-crud-wrapper">

                    <div class="button-wrapper">
                        <x-link
                            text="← Back to detail"
                            x-bind:href="`/admin/posts/${postId}`"
                            class="button-list"
                        />
                    </div>

                    <div>
                        <img
                            class="company-image"
                            :src="previewUrl || (post.image_path
                            ? `/admin/posts/${post.id}/image`
                            : '/images/default-image-company.jpg')"
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

                <form @submit.prevent="save" class="space-y-6 mt-6">

                    <table>

                        <tr>
                            <th class="key-content-item">Title</th>
                            <td colspan="2" class="value-content-item">
                                <input type="text" x-model="form.title" class="input-field">
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Content</th>
                            <td colspan="2" class="value-content-item">
                                <textarea x-model="form.content" rows="6" class="input-field"></textarea>
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Status</th>
                            <td colspan="2" class="value-content-item">
                                <select x-model="form.status" class="input-field">
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending</option>
                                    <option value="publish">Publish</option>
                                    <option value="trash">Trash</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th class="key-content-item">Image</th>
                            <td colspan="2" class="value-content-item">

                                <input type="file" accept="image/*" @change="handleFileUpload">

                                <div class="mt-2 flex space-x-4">
                                    <button
                                        type="button"
                                        class="button-edit"
                                        @click="removeImage()"
                                    >
                                        Remove Image
                                    </button>

                                    <template x-if="previewUrl">
                                        <button
                                            type="button"
                                            class="button-edit"
                                            @click="clearPreview()"
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
                            <x-link
                                text="Cancel"
                                x-bind:href="`/admin/posts/${postId}`"
                                class="button-delete"
                            />
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

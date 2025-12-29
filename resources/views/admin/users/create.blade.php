@extends('_layouts.app')

@section('title','Admin - Create user')

@section('content')

{{--    need fix: add userpic func, Company, Role--}}

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
                <img alt="userpic preview">
            </div>

            <div class="button-wrapper">
                <x-link
                    text="Admin Menu"
                    href="{{ route('admin.index') }}"
                    class="button-list"
                />
            </div>

        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <table>

                <tr>
                    <th class="key-content-item">First name</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="first_name" class="input-field" required>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Second name</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="second_name" class="input-field" required>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">E-mail</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="email" class="input-field" required>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Password</th>
                    <td class="value-content-item" colspan="2">
                        <input type="password" name="password" class="input-field" required>
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Google id</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="google_id" class="input-field">
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Facebook id</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="facebook_id" class="input-field">
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">X id</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="x_id" class="input-field">
                    </td>
                </tr>

{{--                <tr>--}}
{{--                    <th class="key-content-item">Userpic</th>--}}
{{--                    <td class="value-content-item" colspan="2">--}}
{{--                        <input--}}
{{--                            type="file"--}}
{{--                            name="userpic"--}}
{{--                            accept="image/*"--}}
{{--                            @@change="updatePreview"--}}
{{--                        >--}}
{{--                    </td>--}}
{{--                </tr>--}}

                <tr>
                    <th class="key-content-item">Phone</th>
                    <td class="value-content-item" colspan="2">
                        <input type="text" name="phone" class="input-field">
                    </td>
                </tr>

                <tr>
                    <th class="key-content-item">Status account</th>
                    <td class="value-content-item" colspan="2">
                        <select name="status_account" class="input-field">
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </td>
                </tr>

            </table>

            <div class="bottom-crud-wrapper">
                <div class="flex justify-end">
                    <x-button
                        text="Create user"
                        type="submit"
                        class="button-edit"
                    />
                </div>
            </div>

        </form>

    </div>


@endsection

@extends('_layouts.app')

@section('title', 'Admin - Company Detail')

@section('content')

    <div x-data="showCompany({{ $companyId }})" x-init="fetchCompany()">

        <h1>Company</h1>

        <div x-show="loading">Loading...</div>

        <div x-show="!loading">

            <div class="content-item-wrapper">

                <div class="top-crud-wrapper">

                            <div class="button-wrapper">
                                <x-button
                                    text="← Back to list"
                                    href=":`{{ route('admin.companies.index') }}`"
                                    class="button-list"
                                />
                            </div>

                            <div>
                                <img
                                    alt="logo"
                                    :src="company.logo_path ? `/admin/companies/${company.id}/logo` : '/images/default-image-company.jpg'"
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

                <table>

                    <tr>
                        <th class="key-content-item">ID</th>
                        <td colspan="2" class="value-content-item" x-text="company.id"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Name</th>
                        <td colspan="2" class="value-content-item" x-text="company.name"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Address</th>
                        <td colspan="2" class="value-content-item" x-text="company.address"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">City</th>
                        <td colspan="2" class="value-content-item" x-text="company.city"></td>
                    </tr>

                    <tr>
                        <th class="key-content-item">Description</th>
                        <td colspan="2" class="value-content-item" x-text="company.description"></td>
                    </tr>

                </table>

                <div class="bottom-crud-wrapper">

                    <tr class="border-none">
                        <td class="border-none">
                            <div class="flex justify-start">
                                <x-button
                                    text="Company Users"
                                    href=":`/admin/companies/${company.id}/users`"
                                    class="button-link"
                                />
                            </div>
                        </td>

                        <td class="border-none">
                            <div class="flex justify-end">
                                <x-button
                                    text="Company Posts"
                                    href=":`/admin/companies/${company.id}/posts`"
                                    class="button-link"
                                />
                            </div>
                        </td>
                    </tr>

                    <tr class="border-none">
                        <td class="border-none">
                            <div class="flex justify-start">
                                <x-button
                                    text="Edit Company"
                                    href=":`/admin/companies/${company.id}/edit`"
                                    class="button-edit"
                                />
                            </div>
                        </td>

                        <td class="border-none">
                            <div class="flex justify-end">
                                <x-button
                                   text="Delete Company"
                                   href=":`/admin/companies/${company.id}/delete`"
                                   class="button-delete"
                                />
                            </div>
                        </td>
                    </tr>

                </div>

            </div>

        </div>

    </div>

@endsection

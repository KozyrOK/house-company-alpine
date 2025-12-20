@extends('_layouts.app')

@section('title','Admin - Companies')

@section('content')

    <div x-data="adminCompaniesList()" x-init="fetchCompanies()">

        <h1>Companies</h1>

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
                    text="Create New Company"
                    href="{{ route('admin.companies.create') }}"
                    class="button-edit"
                />
            </div>

        </div>

            <div x-show="loading">Loading...</div>

                <div x-show="!loading">


                    <table class="content-item-wrapper">

                        <thead>
                            <tr>
                                <th class="key-content-item-center pr-2">#</th>
                                <th class="key-content-item-center">Name</th>
                                <th class="key-content-item-center">City</th>
                                <th class="key-content-item-center pl-2">Actions</th>
                            </tr>
                        </thead>

                    <tbody>

                    <template x-for="(c, index) in companies" :key="c.id">

                        <tr>
                            <td class="key-content-item" x-text="index + 1"></td>
                            <td class="value-content-item" x-text="c.name"></td>
                            <td class="value-content-item" x-text="c.city"></td>

                            <td>
                                <x-link
                                    text="Detail"
                                    class="button-list"
                                    x-bind:href="`/admin/companies/${c.id}`"
                                />
                            </td>
                        </tr>

                    </template>

                    </tbody>

                </table>
        </div>

    </div>

@endsection

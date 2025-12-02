@extends('_layouts.app')

@section('title','Admin - Companies')

@section('content')

    <div x-data="adminCompaniesList()" x-init="fetchCompanies()">

        <h1>Companies</h1>

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
                            <td class="value-content-item-center" x-text="index + 1"></td>
                            <td class="value-content-item" x-text="c.name"></td>
                            <td class="value-content-item" x-text="c.city"></td>

                            <td>
                                <x-button
                                    text="Detail"
                                    href=':href="`/admin/companies/${c.id}`"'
                                    class="button-list"
                                />
                            </td>
                        </tr>

                    </template>

                    </tbody>

                </table>
        </div>

    </div>

@endsection

@extends('_layouts.app')

@section('title','Admin - Companies')

@section('content')

    <div x-data="adminCompaniesList()" x-init="fetchCompanies()">

        <h1>Companies</h1>

        <div x-show="loading">Loading...</div>

        <table x-show="!loading">
            <thead>
            <tr>
                <th class="content-cell-center">#</th>
                <th class="content-cell-center">Name</th>
                <th class="content-cell-center">City</th>
                <th class="content-cell-center">Actions</th>
            </tr>
            </thead>

            <tbody>
            <template x-for="(c, index) in companies" :key="c.id">
                <tr>
                    <td class="content-cell-center" x-text="index + 1"></td>
                    <td class="content-cell" x-text="c.name"></td>
                    <td class="content-cell" x-text="c.city"></td>

                    <td class="content-cell-center">
                        <x-button
                            text="Edit"
{{--                            :href="'/admin/main/' + c.id"--}}
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

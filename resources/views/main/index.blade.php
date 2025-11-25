@extends('_layouts.app')

@section('title','Companies')

@section('content')

    <div x-data="allCompaniesList()" x-init="fetchCompanies()">

        <h1>{{ __('app.main.my_companies') }}</h1>

        <!-- Loading -->
        <div x-show="loading">Loading...</div>

        <!-- Table -->
        <table x-show="!loading" >
            <thead>
            <tr>
                <th class="content-cell-center">#</th>
                <th class="content-cell-center">{{ __('app.main.name') }}</th>
                <th class="content-cell-center">{{ __('app.main.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            <template x-for="(c, index) in companies" :key="c.id">
                <tr>
                    <td class="content-cell-center" x-text="index + 1"></td>
                    <td class="content-cell" x-text="c.name"></td>
                    <td class="content-cell-center">
                        <x-button
                            text="Detail"
                            href="`/companies/${c.id}`"
                            class="button-list"
                        />
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>

@endsection

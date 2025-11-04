@extends('_layouts.app')

@section('title','Companies')

@section('content')

    <div x-data="allCompaniesList()" x-init="fetchCompanies()">

        <h1>{{ __('app.companies.my_companies') }}</h1>

        <!-- Loading -->
        <div x-show="loading">Loading...</div>

        <!-- Table -->
        <table x-show="!loading" >
            <thead>
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">{{ __('app.companies.name') }}</th>
                <th class="p-2 border">{{ __('app.companies.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            <template x-for="(c, index) in companies" :key="c.id">
                <tr>
                    <td class="p-2 border text-center" x-text="index + 1"></td>
                    <td class="p-2 border" x-text="c.name"></td>
                    <td class="p-2 border text-center">
                        <a :href="`/companies/${c.id}`" class="text-blue-600 hover:underline">Detail</a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>

@endsection

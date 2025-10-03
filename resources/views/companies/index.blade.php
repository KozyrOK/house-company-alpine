<?php

@extends('layouts.app')

@section('title','Companies')

@section('content')
    <div x-data="companiesList()" x-init="fetchCompanies()">
        <h2 class="text-2xl font-bold mb-4">My Companies</h2>

        <!-- Loading -->
        <div x-show="loading" class="text-gray-500">Loading...</div>

        <!-- Companies list -->
        <ul class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3" x-show="!loading">
            <template x-for="c in companies" :key="c.id">
                <li class="p-4 border rounded hover:shadow">
                    <a :href="`/companies/${c.id}/posts`" class="block">
                        <h3 class="font-semibold" x-text="c.name"></h3>
                        <p class="text-sm" x-text="c.address"></p>
                    </a>
                </li>
            </template>
        </ul>
    </div>

    <script>
        function companiesList() {
            return {
                companies: [],
                loading: true,
                async fetchCompanies() {
                    try {
                        const res = await fetch('/api/companies', {credentials:'same-origin'})
                        const data = await res.json()
                        this.companies = data.data ?? data
                    } catch (e) {
                        console.error(e)
                    } finally {
                        this.loading = false
                    }
                }
            }
        }
    </script>
@endsection

@extends('_layouts.app')

@section('title', 'Company')

@section('content')

    <section>

        <h1>Company</h1>

        <div class="content-item-wrapper">

            <div class="top-crud-wrapper">

                <div class="button-wrapper"></div>

                <div>
                    <img alt="logo" src="{{ $company->getLogoUrlAttribute() }}" class="company-image">
                </div>

                <div class="button-wrapper">
{{--                    <x-link text="Dashboard" href="{{ route('dashboard') }}" class="button-list"/>--}}
                </div>

            </div>

            <table class="w-full">
                <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $company->id }}</td></tr>
                <tr><th class="key-content-item">Name</th><td class="value-content-item">{{ $company->name }}</td></tr>
                <tr><th class="key-content-item">Your role in company</th><td class="value-content-item">{{ auth()->user()->roleIn($company) }}</td></tr>
                <tr><th class="key-content-item">Address</th><td class="value-content-item">{{ $company->address ?: '-' }}</td></tr>
                <tr><th class="key-content-item">City</th><td class="value-content-item">{{ $company->city ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Description</th><td class="value-content-item">{{ $company->description ?: '-' }}</td></tr>
                <tr><th class="key-content-item">Users count</th><td class="value-content-item">{{ $company->users_count }}</td></tr>
                <tr><th class="key-content-item">Posts count</th><td class="value-content-item">{{ $company->posts_count }}</td></tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    @if(auth()->user()->hasRole('admin', $company->id))
                        <x-link text="Edit Company" href="{{ route('admin.companies.edit', $company) }}" class="button-edit"/>
                    @endif
                </div>

                <div></div>

                <div class="button-wrapper">
                    @if(auth()->user()->hasRole('admin', $company->id))
                        <x-link text="Company Users" href="{{ route('admin.users.index') }}" class="button-list"/>
                    @else
                        <x-link text="Company Users" href="{{ route('main.users.index') }}" class="button-list"/>
                    @endif
                </div>

            </div>

            @if(auth()->user()->hasRole('admin', $company->id))
                <div x-data="{ open: false }">
                    <div class="flex items-center justify-evenly mb-3">
                        <h3>Request add admin</h3>
                        <button type="button" @click="open = !open" class="button-extend">
                            <span x-show="!open">+</span>
                            <span x-show="open">×</span>
                        </button>
                    </div>

                    <div x-show="open" x-transition>
                        <form method="POST" action="{{ route('company.request-admin') }}">
                            @csrf
                            <select name="user_id" class="input-field" required>
                                <option value="">Select user</option>
                                @foreach(($adminCandidates ?? collect()) as $candidate)
                                    <option value="{{ $candidate->id }}">
                                        {{ $candidate->first_name }} {{ $candidate->second_name }} ({{ $candidate->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="button-wrapper">
                                <x-button text="Add admin" type="submit" class="button-edit"/>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>

    </section>

@endsection

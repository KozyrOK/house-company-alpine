@extends('_layouts.app')

@section('title','Main - Companies')

@section('content')

    <section>

        <h1>Companies</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper"><x-link text="← Back to Main Panel" href="{{ route('main.index') }}" class="button-list"/></div>
            <div></div>
            <div></div>
        </div>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Name</th>
                <th class="key-content-item-center">City</th>
                <th class="key-content-item-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $index => $c)
                <tr>
                    <td class="key-content-item">{{ $companies->firstItem() + $index }}</td>
                    <td class="value-content-item">{{ $c->name }}</td>
                    <td class="value-content-item">{{ $c->city ?: '-' }}</td>
                    <td><x-link text="Detail" class="button-list" href="{{ route('main.companies.show', $c) }}"/></td>
                </tr>
            @empty
                <tr><td colspan="4" class="value-content-item">No companies found.</td></tr>
            @endforelse
            </tbody>
        </table>

    </section>

@endsection

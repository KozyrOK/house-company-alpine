@extends('_layouts.app')

@section('title','Admin - Deleted Companies')

@section('content')
    <section>
        <h1>Deleted Companies</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper">
                <x-link text="← Back to companies" href="{{ route('admin.companies.index') }}" class="button-list"/>
            </div>
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
                    <td class="value-content-item">
                        @can('restore', $c)
                            <form method="POST" action="{{ route('admin.companies.restore', $c->id) }}">
                                @csrf
                                @method('PATCH')
                                <x-button text="Restore" type="submit" class="button-edit"/>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="value-content-item">No deleted companies found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
@endsection

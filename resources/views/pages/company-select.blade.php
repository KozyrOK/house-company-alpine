@extends('_layouts.app')

@section('title', 'Select Company')

@section('content')
    <section>
        <h1>Select current company</h1>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Name</th>
                <th class="key-content-item-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $index => $company)
                <tr>
                    <td class="key-content-item">{{ $index + 1 }}</td>
                    <td class="value-content-item">{{ $company->name }}</td>
                    <td class="value-content-item">
                        <form method="POST" action="{{ route('companies.switch', $company) }}">
                            @csrf
                            <x-button text="Use" type="submit" class="button-edit"/>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endsection

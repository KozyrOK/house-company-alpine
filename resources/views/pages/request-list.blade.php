@extends('_layouts.app')

@section('title', 'Request list')

@section('content')
    <section>
        <h1>Request list</h1>

        <table class="content-item-wrapper">
            <thead>
            <tr>
                <th class="key-content-item-center">#</th>
                <th class="key-content-item-center">Company</th>
                <th class="key-content-item-center">City</th>
                <th class="key-content-item-center">Request status</th>
                <th class="key-content-item-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $index => $company)
                <tr>
                    <td class="key-content-item">{{ $index + 1 }}</td>
                    <td class="value-content-item">{{ $company_user->name }}</td>
                    <td class="value-content-item">{{ $company->city }}</td>
                    <td class="value-content-item">{{ $company_user->status_membership }}</td>
                    <td class="value-content-item">
{{--                        Delete request--}}
{{--                        <form method="POST" action="{{ route('companies.switch', $company) }}">--}}
{{--                            @csrf--}}
{{--                            <x-button text="Use" type="submit" class="button-edit"/>--}}
{{--                        </form>--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endsection


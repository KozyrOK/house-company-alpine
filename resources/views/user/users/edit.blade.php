@extends('_layouts.app')

@section('title', 'Edit User')

@section('content')

    <section class="content-item-wrapper">

        <h1>Edit User</h1>

        <div class="top-crud-wrapper">

            <div class="button-wrapper">
                <x-link text="← Back to detail" href="{{ route('', $user) }}" class="button-list"/>
            </div>

            <div>
                <img class="company-image" src="{{ $user->image_path ?: asset('images/default-image-company.jpg') }}" alt="user image">
            </div>

            <div class="button-wrapper">
                <x-link text="Admin Menu" href="{{ route('') }}" class="button-list"/>
            </div>

        </div>

        <form method="POST" action="{{ route('', $user) }}">
            @csrf
            @method('PATCH')

            <table>
                <tr><th class="key-content-item">First name</th><td colspan="2" class="value-content-item"><input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">Second name</th><td colspan="2" class="value-content-item"><input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">Email</th><td colspan="2" class="value-content-item"><input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">Phone</th><td colspan="2" class="value-content-item"><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field"></td></tr>
                <tr>
                    <th class="key-content-item">Account status</th>
                    <td colspan="2" class="value-content-item">
                        <select name="status_account" class="input-field">
                            @foreach(['pending' => 'Pending', 'active' => 'Active', 'blocked' => 'Blocked'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status_account', $user->status_account) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">

                <div class="button-wrapper">
                    <x-button text="Save" type="submit" class="button-edit"/>
                </div>

                <div class="button-wrapper">
                    <x-link text="Cancel" href="{{ route('', $user) }}" class="button-delete"/>
                </div>

            </div>

        </form>

    </section>

@endsection

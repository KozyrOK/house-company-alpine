@extends('_layouts.app')

@section('title', 'Dashboard')

@section('content')

    @php($user = $user ?? auth()->user())
    @php($isEditMode = request()->boolean('edit'))

    <section>

        <h1>{{ trim(($user->first_name ?? '').' '.($user->second_name ?? '')) ?: $user->email }}</h1>

        <div class="content-item-wrapper">
            <div>
                <img class="company-image" src="{{ $user->image_path ?: asset('images/default-image-company.jpg') }}" alt="user image">
            </div>

            @if($isEditMode)
                <form method="POST" action="{{ route('dashboard.update') }}">
                    @csrf
                    @method('PATCH')
                    <table class="w-full">
                        <tr><th class="key-content-item">First name</th><td class="value-content-item"><input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input-field"></td></tr>
                        <tr><th class="key-content-item">Second name</th><td class="value-content-item"><input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}" class="input-field"></td></tr>
                        <tr><th class="key-content-item">Email</th><td class="value-content-item"><input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field"></td></tr>
                        <tr><th class="key-content-item">Phone</th><td class="value-content-item"><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field"></td></tr>
                        <tr>
                            <th class="key-content-item">Account status</th>
                            <td class="value-content-item">
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

                        <div></div>

                        <div class="button-wrapper">
                            <x-link text="Cancel" href="{{ route('dashboard') }}" class="button-delete"/>
                        </div>
                    </div>
                </form>
            @else
                <table class="w-full">
                    <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $user->id }}</td></tr>
                    <tr><th class="key-content-item">Name</th><td class="value-content-item">{{ $user->first_name }} {{ $user->second_name }}</td></tr>
                    <tr><th class="key-content-item">Email</th><td class="value-content-item">{{ $user->email }}</td></tr>
                    <tr><th class="key-content-item">Phone</th><td class="value-content-item">{{ $user->phone ?: '-' }}</td></tr>
                    <tr><th class="key-content-item">Status</th><td class="value-content-item">{{ $user->status_account ?: '-' }}</td></tr>
                </table>
            @endif

            @if(!$isEditMode && !$user->isSuperAdmin())
                <div class="bottom-crud-wrapper">
                    <div class="button-wrapper">
                        <x-link text="Edit User" href="{{ route('dashboard', ['edit' => 1]) }}" class="button-edit"/>
                    </div>

                    <div></div>

                    <div class="button-wrapper">
                        <form method="POST" action="{{ route('dashboard.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <x-button text="Delete User" type="submit" class="button-delete"/>
                        </form>

                    </div>
                </div>
            @endif
        </div>

    </section>
@endsection

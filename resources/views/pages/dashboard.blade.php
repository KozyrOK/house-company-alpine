@extends('_layouts.app')

@section('title', 'Dashboard')

@section('content')

    @php($user = $user ?? auth()->user())
    @php($isEditMode = request()->boolean('edit'))

    <section>

        <h1>{{ trim(($user->first_name ?? '').' '.($user->second_name ?? '')) ?: $user->email }}</h1>

        <div class="content-item-wrapper">
            <div>
                <img class="company-image" src="{{ $user->avatar_url }}" alt="user image">
            </div>

            @if($isEditMode)
                <form method="POST" action="{{ route('dashboard.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <table class="w-full">
                        <tr><th class="key-content-item">{{__('app.tables.first_name')}}</th><td class="value-content-item"><input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input-field"></td></tr>
                        <tr><th class="key-content-item">{{__('app.tables.second_name')}}</th><td class="value-content-item"><input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}" class="input-field"></td></tr>
                        <tr><th class="key-content-item">{{__('app.tables.e_mail')}}</th><td class="value-content-item"><input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field"></td></tr>
                        <tr><th class="key-content-item">{{__('app.tables.phone')}}</th><td class="value-content-item"><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field"></td></tr>
                        <tr><th class="key-content-item">{{__('app.tables.avatar')}}</th><td class="value-content-item"><input type="file" name="avatar" accept="image/*" class="input-field"></td></tr>
                        <tr>
                            <th class="key-content-item">{{__('app.tables.account_status')}}</th>
                            <td class="value-content-item">
                                <select name="status_account" class="input-field">
                                    @foreach(['pending' => 'Pending', 'active' => 'Active', 'rejected' => 'Rejected', 'deleted' => 'Deleted'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status_account', $user->status_account) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div class="bottom-crud-wrapper">
                        <div class="button-wrapper">
                            <x-button text="app.buttons.save" type="submit" class="button-edit"/>
                        </div>

                        <div></div>

                        <div class="button-wrapper">
                            <x-link text="app.buttons.cancel" href="{{ route('dashboard') }}" class="button-delete"/>
                        </div>
                    </div>
                </form>
            @else
                <table class="w-full">
                    <tr><th class="key-content-item">ID</th><td class="value-content-item">{{ $user->id }}</td></tr>
                    <tr><th class="key-content-item">{{__('app.tables.name')}}</th><td class="value-content-item">{{ $user->first_name }} {{ $user->second_name }}</td></tr>
                    <tr><th class="key-content-item">{{__('app.tables.e_mail')}}</th><td class="value-content-item">{{ $user->email }}</td></tr>
                    <tr><th class="key-content-item">{{__('app.tables.phone')}}</th><td class="value-content-item">{{ $user->phone ?: '-' }}</td></tr>
                    <tr><th class="key-content-item">{{__('app.tables.account_status')}}</th><td class="value-content-item">{{ $user->status_account ?: '-' }}</td></tr>
                </table>
            @endif

            @if(!$isEditMode && !$user->isSuperAdmin())
                <div class="bottom-crud-wrapper">

                    <div class="button-wrapper">
                        <x-link text="app.buttons.edit_user" href="{{ route('dashboard', ['edit' => 1]) }}" class="button-edit"/>
                    </div>


                    <div class="button-wrapper">
                        <form method="POST" action="{{ route('dashboard.destroy') }}" class="confirmable-form" data-confirm-message="Are you sure you want to delete your profile?">
                            @csrf
                            @method('DELETE')
                            <x-button text="app.buttons.delete_user" type="submit" class="button-delete"/>
                        </form>
                    </div>

                    <div class="button-wrapper">
                        <x-link text="app.buttons.users_companies" href="{{ route('company.select') }}" class="button-list"/>
                    </div>

                </div>


            <div x-data="{ open: false }">

                {{-- Header row --}}
                <div class="flex items-center justify-evenly mb-3">

                    <h3>{{__('app.pages.request_company_membership')}}</h3>

                    <button
                        type="button"
                        @click="open = !open"
                        class="button-extend"
                    >
                        <span x-show="!open">+</span>
                        <span x-show="open">×</span>
                    </button>

                </div>

                {{-- Expandable content --}}
                <div x-show="open" x-transition>

                    <form method="POST" action="{{ route('company.request-membership') }}">
                        @csrf

                        <select name="company_id" class="input-field" required>
                            <option value="">{{__('app.buttons.select_company')}}</option>

                            @foreach(($companies ?? collect()) as $company)
                                <option value="{{ $company->id }}">
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="bottom-crud-wrapper">

                            <div class="button-wrapper">
                                <x-button
                                    text="app.buttons.send_request"
                                    type="submit"
                                    class="button-edit"
                                />
                            </div>

                            <div class="button-wrapper">
                                <x-link
                                    text="app.buttons.request_list"
                                    href="{{ route('company.request-list') }}"
                                    class="button-approve"
                                />
                            </div>

                            <div class="button-wrapper">
                                <button type="button" @click="open = false" class="button-delete">
                                    {{__('app.buttons.cancel')}}
                                </button>
                            </div>

                        </div>

                    </form>

                </div>

            </div>

            @endif

        </div>

    </section>
@endsection

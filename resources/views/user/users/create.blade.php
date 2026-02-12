@extends('_layouts.app')

@section('title', 'Create User')

@section('content')
    <section>
        <h1>Create user</h1>

        <form method="POST" action="#" class="content-item-wrapper section-spacing form-grid">
            @csrf

            <label class="form-field">
                <span class="form-label">First name</span>
                <input type="text" name="first_name" class="input-field" required>
            </label>

            <label class="form-field">
                <span class="form-label">Last name</span>
                <input type="text" name="second_name" class="input-field" required>
            </label>

            <label class="form-field">
                <span class="form-label">Email</span>
                <input type="email" name="email" class="input-field" required>
            </label>

            <label class="form-field">
                <span class="form-label">Password</span>
                <input type="password" name="password" class="input-field" required>
            </label>

            <label class="form-field">
                <span class="form-label">Phone</span>
                <input type="text" name="phone" class="input-field">
            </label>

            <x-button text="Save" type="submit" class="button-edit"/>
        </form>
    </section>
@endsection

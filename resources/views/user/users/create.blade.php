@extends('_layouts.app')

@section('title', 'Create User')

@section('content')
    <section>
        <h1>Create user</h1>

        <form method="POST" action="#" class="content-item-wrapper mt-4 grid gap-4">
            @csrf

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">First Name</span>
                <input type="text" name="first_name" class="input-field" required>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Second Name</span>
                <input type="text" name="second_name" class="input-field" required>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Email</span>
                <input type="email" name="email" class="input-field" required>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Password</span>
                <input type="password" name="password" class="input-field" required>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Phone</span>
                <input type="text" name="phone" class="input-field">
            </label>

            <x-button text="Save" type="submit" class="button-edit"/>
        </form>
    </section>
@endsection

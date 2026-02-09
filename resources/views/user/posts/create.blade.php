@extends('_layouts.app')

@section('title', 'Create Post')

@section('content')
    <section>
        <h1>Create post</h1>

        <form method="POST" action="#" class="content-item-wrapper mt-4 grid gap-4">
            @csrf

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Title</span>
                <input type="text" name="title" class="input-field" required>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Content</span>
                <textarea name="content" rows="6" class="input-field" required></textarea>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Status</span>
                <select name="status" class="input-field">
                    @foreach(['draft', 'future', 'pending', 'publish'] as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </label>

            <x-button text="Save" type="submit" class="button-edit"/>
        </form>
    </section>
@endsection

@extends('_layouts.app')

@section('title', 'Edit Post')

@section('content')
    <section>
        <h1>Edit post</h1>

        <form method="POST" action="#" class="content-item-wrapper mt-4 grid gap-4">
            @csrf
            @method('PATCH')

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Title</span>
                <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" class="input-field" required>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Content</span>
                <textarea name="content" rows="6" class="input-field" required>{{ old('content', $post->content ?? '') }}</textarea>
            </label>

            <label class="grid gap-1">
                <span class="text-sm text-slate-500">Status</span>
                <select name="status" class="input-field">
                    @foreach(['draft', 'future', 'pending', 'publish', 'trash'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $post->status ?? '') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </label>

            <x-button text="Save" type="submit" class="button-edit"/>
        </form>
    </section>
@endsection

@extends('_layouts.app')

@section('title', 'Admin – Edit Post')

@section('content')
    <section class="content-item-wrapper">
        <h1>Edit Post</h1>

        <div class="top-crud-wrapper">
            <div class="button-wrapper"><x-link text="← Back to detail" href="{{ route('admin.posts.show', $post) }}" class="button-list"/></div>
            <div></div>
            <div class="button-wrapper"><x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/></div>
        </div>

        <form method="POST" action="{{ route('admin.posts.update', $post) }}">
            @csrf
            @method('PATCH')

            <table>
                <tr>
                    <th class="key-content-item">Company</th>
                    <td colspan="2" class="value-content-item">
                        <select name="company_id" class="input-field">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id', $post->company_id) == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr><th class="key-content-item">Title</th><td colspan="2" class="value-content-item"><input type="text" name="title" value="{{ old('title', $post->title) }}" class="input-field"></td></tr>
                <tr><th class="key-content-item">Content</th><td colspan="2" class="value-content-item"><textarea name="content" class="input-field" rows="6">{{ old('content', $post->content) }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">Status</th>
                    <td colspan="2" class="value-content-item">
                        <select name="status" class="input-field">
                            @foreach(['draft','future','pending','publish','trash'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $post->status) === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">
                <div class="flex justify-start"><x-button text="Save" type="submit" class="button-edit"/></div>
                <div class="flex justify-end"><x-link text="Cancel" href="{{ route('admin.posts.show', $post) }}" class="button-delete"/></div>
            </div>
        </form>
    </section>
@endsection

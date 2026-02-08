@extends('_layouts.app')

@section('title', 'Admin - Create Post')

@section('content')
    <section class="content-item-wrapper">
        <div class="top-crud-wrapper">
            <div class="button-wrapper"><x-link text="← Back to list" href="{{ route('admin.posts.index') }}" class="button-list"/></div>
            <div></div>
            <div class="button-wrapper"><x-link text="Admin Menu" href="{{ route('admin.index') }}" class="button-list"/></div>
        </div>

        <form action="{{ route('admin.posts.store') }}" method="POST">
            @csrf
            <table>
                <tr>
                    <th class="key-content-item">Company</th>
                    <td colspan="2" class="value-content-item">
                        <select name="company_id" class="input-field" required>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr><th class="key-content-item">Title</th><td colspan="2" class="value-content-item"><input type="text" name="title" class="input-field" value="{{ old('title') }}" required></td></tr>
                <tr><th class="key-content-item">Content</th><td colspan="2" class="value-content-item"><textarea name="content" rows="6" class="input-field" required>{{ old('content') }}</textarea></td></tr>
                <tr>
                    <th class="key-content-item">Status</th>
                    <td colspan="2" class="value-content-item">
                        <select name="status" class="input-field">
                            @foreach(['draft','future','pending','publish','trash'] as $status)
                                <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>

            <div class="bottom-crud-wrapper">
                <div class="flex justify-end"><x-button text="Create Post" type="submit" class="button-edit"/></div>
            </div>
        </form>
    </section>
@endsection

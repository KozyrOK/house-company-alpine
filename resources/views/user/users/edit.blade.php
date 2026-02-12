<div class="content-item-wrapper">
    <h2 class="section-title">Edit profile</h2>

    @if(session('status'))
        <div class="status-message">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('dashboard.update') }}" class="form-grid section-spacing">
        @csrf
        @method('PATCH')

        <label class="form-field">
            <span class="form-label">First name</span>
            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input-field">
        </label>

        <label class="form-field">
            <span class="form-label">Last name</span>
            <input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}" class="input-field">
        </label>

        <label class="form-field">
            <span class="form-label">Email</span>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field">
        </label>

        <label class="form-field">
            <span class="form-label">Phone</span>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field">
        </label>

        <div class="form-actions">
            <x-button text="Save" type="submit" class="button-edit"/>
        </div>
    </form>

    <form method="POST" action="{{ route('dashboard.destroy') }}" class="mt-4">
        @csrf
        @method('DELETE')
        <x-button text="Delete account" type="submit" class="button-list"/>
    </form>
</div>

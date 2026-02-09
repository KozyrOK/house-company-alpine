<div class="content-item-wrapper">
    <h2 class="text-xl font-semibold">Edit User</h2>

    @if(session('status'))
        <div class="mt-3 text-sm text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('dashboard.update') }}" class="mt-4 grid gap-4">
        @csrf
        @method('PATCH')

        <label class="grid gap-1">
            <span class="text-sm text-slate-500">First name</span>
            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input-field">
        </label>

        <label class="grid gap-1">
            <span class="text-sm text-slate-500">Second name</span>
            <input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}" class="input-field">
        </label>

        <label class="grid gap-1">
            <span class="text-sm text-slate-500">Email</span>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field">
        </label>

        <label class="grid gap-1">
            <span class="text-sm text-slate-500">Phone</span>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field">
        </label>

        <div class="flex flex-wrap gap-2">
            <x-button text="Save" type="submit" class="button-edit"/>
        </div>
    </form>

    <form method="POST" action="{{ route('dashboard.destroy') }}" class="mt-4">
        @csrf
        @method('DELETE')
        <x-button text="Delete user" type="submit" class="button-list"/>
    </form>
</div>

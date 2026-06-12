<form method="GET" action="{{ url()->current() }}" class="content-item-wrapper">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

        <label class="value-content-item">
            <span class="block font-semibold">{{__('app.filters.status_membership')}}</span>
            <select name="status_membership" class="input-field">
                <option value="">{{__('app.filters.all_statuses')}}</option>
                @foreach(['pending', 'active', 'invited', 'pending_admin', 'deleted', 'rejected'] as $status)
                    <option value="{{ $status }}" @selected(request('status_membership') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </label>

        <label class="value-content-item">
            <span class="block font-semibold">{{__('app.filters.role')}}</span>
            <select name="role" class="input-field">
                <option value="">{{__('app.filters.all_roles')}}</option>
                @foreach(['admin', 'company_head', 'user'] as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ $role }}</option>
                @endforeach
            </select>
        </label>

        <div class="flex flex-wrap gap-2 justify-center md:justify-end">
            <x-button text="app.buttons.apply" type="submit" class="button-list"/>
            <x-link text="app.buttons.reset" href="{{ url()->current() }}" class="button-delete"/>
        </div>

    </div>

</form>

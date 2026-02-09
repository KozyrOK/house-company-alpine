<div class="content-item-wrapper">
    <h2 class="text-xl font-semibold">User profile</h2>

    <dl class="mt-4 grid gap-2">
        <div>
            <dt class="text-sm text-slate-500">First name</dt>
            <dd class="text-base">{{ $user->first_name }} {{ $user->second_name }}</dd>
        </div>
        <div>
            <dt class="text-sm text-slate-500">Email</dt>
            <dd class="text-base">{{ $user->email }}</dd>
        </div>
        <div>
            <dt class="text-sm text-slate-500">Phone</dt>
            <dd class="text-base">{{ $user->phone ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-sm text-slate-500">Status</dt>
            <dd class="text-base">{{ $user->status_account ?? 'pending' }}</dd>
        </div>
        <div>
            <dt class="text-sm text-slate-500">Companies</dt>
            <dd class="text-base">{{ $user->companies->pluck('name')->join(', ') ?: '—' }}</dd>
        </div>
    </dl>
</div>

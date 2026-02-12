<div class="content-item-wrapper">
    <h2 class="section-title">User profile</h2>

    <dl class="list-grid">
        <div>
            <dt class="text-meta">First name</dt>
            <dd class="text-body">{{ $user->first_name }} {{ $user->second_name }}</dd>
        </div>
        <div>
            <dt class="text-meta">Email</dt>
            <dd class="text-body">{{ $user->email }}</dd>
        </div>
        <div>
            <dt class="text-meta">Phone</dt>
            <dd class="text-body">{{ $user->phone ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-meta">Status</dt>
            <dd class="text-body">{{ $user->status_account ?? 'pending' }}</dd>
        </div>
        <div>
            <dt class="text-meta">Companies</dt>
            <dd class="text-body">{{ $user->companies->pluck('name')->join(', ') ?: '—' }}</dd>
        </div>
    </dl>
</div>

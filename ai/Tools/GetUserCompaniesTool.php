<?php

namespace App\Ai\Tools;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class GetUserCompaniesTool
{
    public function handle(User $user): array
    {
        $query = $user->isSuperAdmin()
            ? Company::query()
            : $user->companies()->wherePivotIn('status_membership', ['active', 'pending_admin']);

        return $query->where('status_company', 'active')->orderBy('name')->get()->filter(
            fn (Company $company) => Gate::forUser($user)->allows('view', $company) || $user->isSuperAdmin()
        )->map(fn (Company $company) => [
            'id' => $company->id,
            'name' => $company->name,
            'city' => $company->city,
            'status' => $company->status_company,
            'role' => $user->isSuperAdmin() ? 'superadmin' : $user->roleIn($company),
        ])->values()->all();
    }
}
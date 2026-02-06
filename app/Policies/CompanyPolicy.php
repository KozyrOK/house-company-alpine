<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->companies()
            ->wherePivotIn('role', ['admin', 'company_head'])
            ->exists();
    }

    public function view(User $user, Company $company): bool
    {
        return $user->belongsToCompany($company->id);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Company $company): bool
    {
        return $user->isAdminOrHigher($company->id);
    }

    public function delete(User $user, Company $company): bool
    {
        return false;
    }
}

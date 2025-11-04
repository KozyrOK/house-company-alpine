<?php

namespace App\Policies;

use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, int $companyId): bool
    {
        return $user->belongsToCompany($companyId) || $user->isSuperAdmin($companyId);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, int $companyId): bool
    {
        return $user->isAdminOrHigher($companyId) || $user->isSuperAdmin($companyId);
    }

    public function delete(User $user, int $companyId): bool
    {
        return $user->isSuperAdmin($companyId);
    }
}

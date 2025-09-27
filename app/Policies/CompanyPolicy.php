<?php

namespace App\Policies;

use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin(0);
    }

    public function view(User $user, int $companyId): bool
    {
        return $user->belongsToCompany($companyId) || $user->isSuperAdmin(0);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin(0);
    }

    public function update(User $user, int $companyId): bool
    {
        return $user->isAdminOrHigher($companyId) || $user->isSuperAdmin(0);
    }

    public function delete(User $user, int $companyId): bool
    {
        return $user->isSuperAdmin(0);
    }
}

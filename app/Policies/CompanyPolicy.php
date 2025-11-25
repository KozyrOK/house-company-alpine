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

    public function viewAny(User $user, string $model): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, Company|int $company): bool
    {
        $id = $company instanceof Company ? $company->id : $company;
        return $user->belongsToCompany($id);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Company|int $company): bool
    {
        $id = $company instanceof Company ? $company->id : $company;
        return $user->isAdminOrHigher($id);
    }

    public function delete(User $user, Company|int $company): bool
    {
        return false;
    }
}

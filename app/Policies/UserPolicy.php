<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class UserPolicy
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
        return false;
    }

    public function view(User $user, User $model, Company $company): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        return $user->isAdminOrHigher($company->id) && $model->belongsToCompany($company->id);
    }

    public function create(User $user, Company $company): bool
    {
        return $user->isAdminOrHigher($company->id);
    }

    public function update(User $user, User $model, Company $company): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        return $user->isAdminOrHigher($company->id) && $model->belongsToCompany($company->id);
    }

    public function delete(User $user, Company $company): bool
    {
        return false;
    }

    public function approve(User $user, User $model, Company $company): bool
    {
        return $user->isAdminOrHigher($company->id) && $model->belongsToCompany($company->id);
    }
}

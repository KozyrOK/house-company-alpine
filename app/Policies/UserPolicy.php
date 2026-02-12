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
        return $user->companies()
            ->wherePivotIn('role', ['admin'])
            ->exists();
    }

    public function view(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        return $user->companies()
            ->wherePivotIn('role', ['admin', 'company_head'])
            ->whereIn('company_id', $model->companies()->select('companies.id'))
            ->exists();
    }

    public function create(User $user, Company $company): bool
    {
        return $user->isCompanyHeadOrHigher($company->id);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        return $user->companies()
            ->wherePivotIn('role', ['admin', 'company_head'])
            ->whereIn('company_id', $model->companies()->select('companies.id'))
            ->exists();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->companies()
            ->wherePivotIn('role', ['admin'])
            ->whereIn('company_id', $model->companies()->select('companies.id'))
            ->exists();
    }

    public function approve(User $user, User $model): bool
    {
        return $user->companies()
            ->wherePivotIn('role', ['admin'])
            ->whereIn('company_id', $model->companies()->select('companies.id'))
            ->exists();
    }
}

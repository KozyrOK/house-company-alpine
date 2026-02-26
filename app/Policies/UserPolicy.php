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

    /**
     * GET /users
     */
    public function viewAny(User $user): bool
    {
        return $user->companies()
            ->wherePivotIn('role', ['admin'])
            ->exists();
    }

    /**
     * GET /users/{user}
     */
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

    /**
     * POST /users
     */
    public function create(User $user, Company $company): bool
    {
        return $user->hasRole(['admin', 'company_head'], $company->id);
    }

    /**
     * PUT/PATCH /users/{user}
     */
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

    /**
     * DELETE /users/{user}
     */
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

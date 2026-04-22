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
        return currentCompany() !== null && $user->hasRole(['admin', 'company_head', 'user'], currentCompany()->id);
    }

    public function view(User $user, User $model): bool
    {
        $current = currentCompany();
        if (!$current) {
            return false;
        }

        return $model->belongsToCompany($current->id);
    }

    public function create(User $user, Company $company): bool
    {
        $current = currentCompany();

        return $current !== null
            && $company->id === $current->id
            && $user->hasRole(['admin', 'company_head'], $company->id);
    }

    public function update(User $user, User $model): bool
    {
        $current = currentCompany();
        if (!$current || !$model->belongsToCompany($current->id)) {
            return false;
        }

        if ($user->id === $model->id) {
            return true;
        }

        $actorRole = $user->roleIn($current);
        $targetRole = $model->roleIn($current);

        return ($actorRole === 'admin' && in_array($targetRole, ['company_head', 'user'], true))
            || ($actorRole === 'company_head' && $targetRole === 'user');
    }

    public function delete(User $user, User $model): bool
    {
        $current = currentCompany();

        return $current !== null
            && $model->belongsToCompany($current->id)
            && $user->hasRole('admin', $current->id);
    }

    public function restore(User $user, User $model): bool
    {
        $current = currentCompany();

        if (!$current || !$model->belongsToCompany($current->id)) {
            return false;
        }

        if ($model->deleted_by === $user->id) {
            return true;
        }

        return $user->hasRole('admin', $current->id);
    }

    public function approve(User $user, User $model): bool
    {
        $current = currentCompany();

        return $current !== null
            && $model->belongsToCompany($current->id)
            && $user->hasRole('admin', $current->id);
    }
}

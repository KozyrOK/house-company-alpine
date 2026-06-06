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
        if ($user->id === $model->id) {
            return true;
        }

        $current = currentCompany();
        if (!$current) {
            return false;
        }

        return $model->belongsToCompany($current->id)
            && $user->hasRole(['admin', 'company_head'], $current->id);
    }

    public function create(User $user, ?Company $company = null): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        $current = currentCompany();
        if (!$current || !$model->belongsToCompany($current->id)) {
            return false;
        }

        $actorRole = $user->roleIn($current);
        $targetRole = $model->roleIn($current);

        return ($actorRole === 'admin' && in_array($targetRole, ['company_head', 'user'], true))
            || ($actorRole === 'company_head' && $targetRole === 'user');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function approve(User $user, User $model): bool
    {
        $current = currentCompany();

        return $current !== null
            && $model->belongsToCompany($current->id)
            && $user->hasRole('admin', $current->id);
    }
}

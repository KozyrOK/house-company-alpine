<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user, int $companyId): bool
    {
        return $user->isAdminOrHigher($companyId) || $user->isSuperAdmin($companyId);
    }

    public function view(User $user, User $model, int $companyId): bool
    {
        return $user->id === $model->id
            || $user->isAdminOrHigher($companyId)
            || $user->isSuperAdmin($companyId);
    }

    public function create(User $user, int $companyId): bool
    {
        return $user->isAdminOrHigher($companyId) || $user->isSuperAdmin($companyId);
    }

    public function update(User $user, User $model, int $companyId): bool
    {
        return $user->id === $model->id
            || $user->isAdminOrHigher($companyId)
            || $user->isSuperAdmin($companyId);
    }

    public function delete(User $user, User $model, int $companyId): bool
    {
        return $user->isSuperAdmin($companyId);
    }

    public function approve(User $user, User $model, int $companyId): bool
    {
        return $user->isAdminOrHigher($companyId) || $user->isSuperAdmin($companyId);
    }
}

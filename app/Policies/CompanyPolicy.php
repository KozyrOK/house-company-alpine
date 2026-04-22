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
        return currentCompany() !== null;
    }

    public function view(User $user, Company $company): bool
    {
        $current = currentCompany();

        return $current !== null && $company->id === $current->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Company $company): bool
    {
        return $this->view($user, $company) && $user->hasRole('admin', $company->id);
    }

    public function delete(User $user, Company $company): bool
    {
        return false;
    }

    public function restore(User $user, Company $company): bool
    {
        if (!$this->view($user, $company)) {
            return false;
        }

        return $company->deleted_by === $user->id;
    }
}

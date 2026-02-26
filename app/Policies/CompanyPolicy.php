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

    /**
     * GET /companies
     */
    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    /**
     * GET /companies/{company}
     */
    public function view(User $user, Company $company): bool
    {
        return $user->belongsToCompany($company->id);
    }

    /**
     * POST /companies
     * Only superadmin (by before)
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * PUT/PATCH /companies/{company}
     * admin own company
     */
    public function update(User $user, Company $company): bool
    {
        return $user->hasRole('admin', $company->id);
    }

    /**
     * DELETE /companies/{company}
     * only superadmin
     */
    public function delete(User $user, Company $company): bool
    {
        return false;
    }
}

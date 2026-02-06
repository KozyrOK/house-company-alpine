<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Models\Post;

class PostPolicy
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
            ->wherePivotIn('role', ['admin', 'company_head'])
            ->exists();
    }

    public function view(User $user, Post $post): bool
    {
        return $user->belongsToCompany($post->company_id);
    }

    public function create(User $user, ?Company $company = null): bool
    {
        if (!$company) {
            return $user->companies()->exists();
        }

        return $user->belongsToCompany($company->id);
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id
            || $user->isCompanyHeadOrHigher($post->company_id);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }

    public function approve(User $user, Post $post): bool
    {
        $companyId = $post->company_id;
        return $user->isCompanyHeadOrHigher($companyId);
    }
}

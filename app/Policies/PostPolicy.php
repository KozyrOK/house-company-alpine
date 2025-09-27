<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    public function viewAny(User $user, int $companyId): bool
    {
        return $user->belongsToCompany($companyId);
    }

    public function view(User $user, Post $post, int $companyId): bool
    {
        return $user->belongsToCompany($companyId);
    }

    public function create(User $user, int $companyId): bool
    {
        return $user->hasRole(['user','company_head','admin','superadmin'], $companyId);
    }

    public function update(User $user, Post $post, int $companyId): bool
    {
        return $user->id === $post->user_id
            || $user->isCompanyHeadOrHigher($companyId);
    }

    public function delete(User $user, Post $post, int $companyId): bool
    {
        return $user->id === $post->user_id
            || $user->isCompanyHeadOrHigher($companyId);
    }

    public function approve(User $user, Post $post, int $companyId): bool
    {
        return $user->isCompanyHeadOrHigher($companyId);
    }
}

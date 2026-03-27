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

    /**
     * GET /posts
     */
    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    /**
     * GET /posts/{post}
     */
    public function view(User $user, Post $post): bool
    {
        return $user->belongsToCompany($post->company_id);
    }

    /**
     * POST /posts
     */
    public function create(User $user, Company $company): bool
    {
        return $user->belongsToCompany($company->id);
    }

    /**
     * PUT/PATCH /posts/{post}
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->id === $post->user_id) {
            return true;
        }

        if ($user->hasRole('admin', $post->company_id)) {
            return true;
        }

            if ($user->hasRole('company_head', $post->company_id)) {
                $authorRole = $post->user?->roleInCompany($post->company);
                return in_array($authorRole, ['user', 'company_head'], true);
        }

        return false;
    }

    /**
     * DELETE /posts/{post}
     */
    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }

    public function approve(User $user, Post $post): bool
    {
        return $user->hasRole(['company_head', 'admin'], $post->company_id);
    }
}

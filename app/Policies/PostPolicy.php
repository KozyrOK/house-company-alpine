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
        return currentCompany() !== null;
    }

    public function view(User $user, Post $post): bool
    {
        $current = currentCompany();

        return $current !== null && $post->company_id === $current->id;
    }

    public function create(User $user, Company $company): bool
    {
        $current = currentCompany();

        return $current !== null
            && $company->id === $current->id
            && $user->belongsToCompany($company->id);
    }

    public function update(User $user, Post $post): bool
    {
        if (!$this->view($user, $post)) {
            return false;
        }

        if ($user->id === $post->user_id) {
            return true;
        }

        if ($user->hasRole('admin', $post->company_id)) {
            return true;
        }

        if ($user->hasRole('company_head', $post->company_id)) {
            $authorRole = $post->user?->roleIn($post->company);
            return in_array($authorRole, ['user', 'company_head'], true);
        }

        return false;
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }

    public function restore(User $user, Post $post): bool
    {
        if (!$this->view($user, $post)) {
            return false;
        }

        if ($post->deleted_by === $user->id) {
            return true;
        }

        return $user->hasRole(['admin', 'company_head'], $post->company_id);
    }

    public function approve(User $user, Post $post): bool
    {
        return $this->view($user, $post) && $user->hasRole(['company_head', 'admin'], $post->company_id);
    }
}

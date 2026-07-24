<?php

namespace App\Ai\Tools;

use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class GetCurrentUserPermissionsTool
{
    public function handle(User $user): array
    {
        $company = currentCompany();

        return [
            'is_superadmin' => $user->isSuperAdmin(),
            'current_company' => $company ? ['id' => $company->id, 'name' => $company->name] : null,
            'role' => $company ? $user->roleIn($company) : ($user->isSuperAdmin() ? 'superadmin' : null),
            'can_view_companies' => Gate::forUser($user)->allows('viewAny', Company::class),
            'can_view_posts' => Gate::forUser($user)->allows('viewAny', Post::class),
            'can_view_users' => Gate::forUser($user)->allows('viewAny', User::class),
            'can_access_admin_panel' => $user->canAccessAdminPanel(),
            'can_access_main_panel' => $user->canAccessMainPanel(),
        ];
    }
}
<?php

namespace App\Ai\Middleware;

use App\Models\User;

class AttachTenantContextMiddleware
{
    public function context(User $user): array
    {
        $company = currentCompany();
        return [
            'user_id' => $user->id,
            'current_company_id' => $company?->id,
            'current_company_name' => $company?->name,
            'is_superadmin' => $user->isSuperAdmin(),
        ];
    }
}
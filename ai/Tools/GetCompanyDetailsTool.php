<?php

namespace App\Ai\Tools;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class GetCompanyDetailsTool
{
    public function handle(User $user, int $companyId): ?array
    {
        $company = Company::withCount(['users', 'posts'])->find($companyId);
        if (!$company || Gate::forUser($user)->denies('view', $company)) return null;

        return [
            'id' => $company->id,
            'name' => $company->name,
            'address' => $company->address,
            'city' => $company->city,
            'description' => $company->description,
            'status' => $company->status_company,
            'users_count' => $company->users_count,
            'posts_count' => $company->posts_count,
        ];
    }
}
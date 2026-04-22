<?php

use App\Models\Company;

if (!function_exists('currentCompany')) {
    function currentCompany(): ?Company
    {
        if (!auth()->check()) {
            return null;
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return null;
        }

        $companyId = (int) session('current_company_id');
        if ($companyId <= 0) {
            return null;
        }

        return $user->companies()->where('companies.id', $companyId)->first();
    }
}

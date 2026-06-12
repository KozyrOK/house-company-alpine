<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCurrentCompanyIsSet
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $currentCompanyId = (int) $request->session()->get('current_company_id');
        $companyIds = $user->companies()
            ->where('companies.status_company', 'active')
            ->wherePivotIn('status_membership', ['active', 'pending_admin'])
            ->pluck('companies.id');

        if ($currentCompanyId > 0 && $companyIds->contains($currentCompanyId)) {
            return $next($request);
        }

        if ($companyIds->count() === 1) {
            $request->session()->put('current_company_id', (int) $companyIds->first());
            return $next($request);
        }

        if ($request->routeIs(
            'company.select',
            'companies.switch',
            'company.request-membership',
            'company.request-list',
            'company.request-list.delete',
            'dashboard',
            'dashboard.*',
            'info',
            'locale.switch',
            'theme.switch',
            'logout',
            'verification.*'
        )) {
            return $next($request);
        }

        if ($companyIds->isEmpty()) {
            return $next($request);
        }

        return redirect()->route('company.select');
    }
}

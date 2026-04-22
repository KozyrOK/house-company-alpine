<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MainAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $company = currentCompany();

        if (!$company || !in_array($user->roleIn($company), ['company_head', 'user'], true)) {
            abort(403);
        }

        return $next($request);
    }
}

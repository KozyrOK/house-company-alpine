<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        if (!$user->isSuperAdmin() && !$user->isAdminInAnyCompany()) {
            abort(403);
        }

        return $next($request);
    }
}

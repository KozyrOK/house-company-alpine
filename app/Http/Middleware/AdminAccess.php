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

        if (!$user->companies()
            ->wherePivotIn('role', ['admin', 'superadmin'])
            ->exists()) {
            abort(403);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeApiResource
{
    public function handle(Request $request, Closure $next, string $ability, string $model)
    {
        $user = $request->user();

        if ($request->route('company')) {
            $companyId = (int) $request->route('company');

            if (!$user->can($ability, [$model, $companyId])) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
            }

            return $next($request);
        }

        if (!$user->can($ability, $model)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

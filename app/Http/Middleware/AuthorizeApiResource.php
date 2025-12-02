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

        $routeParams = $request->route()->parameters();

        $resourceParam = null;

        foreach ($routeParams as $key => $value) {

            if (strtolower($key) === strtolower(class_basename($model))) {
                $resourceParam = $value;
                break;
            }
        }

        if ($resourceParam !== null) {

            if (is_object($resourceParam)) {
                $instance = $resourceParam;
            }

            else {
                $instance = $model::findOrFail($resourceParam);
            }

            if (!$user->can($ability, $instance)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return $next($request);
        }

        if (!$user->can($ability, $model)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}

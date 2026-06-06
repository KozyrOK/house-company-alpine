<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserPreferences
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $locale = in_array($user->locale, ['en', 'uk', 'ru'], true) ? $user->locale : 'en';
            $theme = in_array($user->theme, ['light', 'dark', 'system'], true) ? $user->theme : 'system';

            App::setLocale($locale);
            $request->session()->put('locale', $locale);
            $request->session()->put('theme', $theme);
        }

        return $next($request);
    }
}

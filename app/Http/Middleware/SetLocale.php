<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $available = ['en', 'uk', 'ru'];
        $locale = Session::get('locale', config('app.locale', 'en'));

        if (!in_array($locale, $available)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}

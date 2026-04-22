<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        require_once app_path('Support/helpers.php');
    }

    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('views/_layouts'), 'layouts');
        Blade::anonymousComponentPath(resource_path('views/auth'), 'auth');
        Blade::anonymousComponentPath(resource_path('views/pages'), 'pages');

        //View::share('isDark', request()->cookie('darkMode') === 'true');
    }
}

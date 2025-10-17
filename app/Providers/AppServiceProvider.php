<?php

namespace App\Providers;

use App\Http\Controllers\API\UserController;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * This service provider is intended for application-wide services and configuration.
     *
     * According to the project requirements (see Technical Specification),
     * possible future functionality for this file may include:
     *
     * - Global pagination style (e.g. Bootstrap pagination for posts listing).
     * - Localization setup (multi-language support, default language selection).
     * - Service bindings for SOLID architecture (e.g. ChatBotContract -> ChatBotService).
     * - Global macros or helpers for collections, strings, etc.
     *
     */

    /**
     * Register any application services.
     */

    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //View::share('isDark', request()->cookie('darkMode') === 'true');
    }
}

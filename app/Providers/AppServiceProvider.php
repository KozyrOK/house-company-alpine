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

class AppServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        Company::class => CompanyPolicy::class,
    ];
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
        $this->registerPolicies();
    }
}

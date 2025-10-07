<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Blade-based frontend routes.
| Role and access control handled by User model methods and Policies.
|--------------------------------------------------------------------------
*/

// ---------- Home redirect ----------
// Guests → /info
// Authenticated users → /companies
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('companies.index')
        : redirect()->route('info');
});

// ---------- Public pages ----------
Route::view('/info', 'header.info')->name('info');


// ---------- Authenticated users ----------
Route::middleware('auth')->group(function () {

    // Main route after login
    Route::get('/main', fn() => redirect()->route('companies.index'))->name('main');

    // ---------- Companies ----------
    Route::get('/companies', [CompanyController::class, 'index'])
        ->name('companies.index');

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->name('companies.show')
        ->can('view', 'company');

    // ---------- Posts ----------
    Route::get('/companies/{company}/posts', [PostController::class, 'index'])
        ->name('companies.posts.index')
        ->can('view', 'company');

    Route::get('/companies/{company}/posts/{post}', [PostController::class, 'show'])
        ->name('companies.posts.show')
        ->can('view', 'post');

    // ---------- Profile ----------
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // ---------- Forum & Chat ----------
    Route::view('/forum', 'header.forum')->name('forum');
    Route::view('/chat', 'header.chat')->name('chat');

    // ---------- Admin area ----------
    Route::prefix('admin')->group(function () {

        // Dashboard
        Route::view('/', 'admin.dashboard')
            ->name('admin.dashboard')
            ->can('viewAny', App\Models\User::class);

        // Users
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index')
            ->can('viewAny', App\Models\User::class);

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('admin.users.show')
            ->can('view', 'user');

        // Companies
        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('admin.companies.index')
            ->can('viewAny', App\Models\Company::class);

        // Posts
        Route::get('/posts', [PostController::class, 'index'])
            ->name('admin.posts.index')
            ->can('viewAny', App\Models\Post::class);
    });
});

require __DIR__.'/auth.php';

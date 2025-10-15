<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ProfileController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('companies.index')
        : redirect()->route('info');
});

// Public pages
Route::view('/info', 'components.info')->name('info');

// Authenticated users
Route::middleware('auth')->group(function () {

    Route::get('/main', fn() => redirect()->route('companies.index'))->name('main');

    Route::get('/companies', [CompanyController::class, 'index'])
        ->name('companies.index');

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->name('companies.show')
        ->can('view', 'company');

    Route::get('/companies/{company}/posts', [PostController::class, 'index'])
        ->name('companies.posts.index')
        ->can('view', 'company');

    Route::get('/companies/{company}/posts/{post}', [PostController::class, 'show'])
        ->name('companies.posts.show')
        ->can('view', 'post');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::view('/forum', 'components.forum')->name('forum');
    Route::view('/chat', 'components.chat')->name('chat');

    Route::prefix('superadmin')->group(function () {

        Route::view('/', 'components.superadmin')
            ->name('main-component.superadmin');

        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index')
            ->can('viewAny', App\Models\User::class);

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('admin.users.show')
            ->can('view', 'user');

        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('admin.companies.index')
            ->can('viewAny', App\Models\Company::class);

        Route::get('/posts', [PostController::class, 'index'])
            ->name('admin.posts.index')
            ->can('viewAny', App\Models\Post::class);
    });
});

require __DIR__.'/auth.php';

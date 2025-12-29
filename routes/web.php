<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ProfileController;

// LOCALE SWITCHER

Route::post('/locale/{locale}', function ($locale) {
    $available = ['en', 'uk', 'ru'];

    if (!in_array($locale, $available)) {
        $locale = 'en';
    }

    Session::put('locale', $locale);
    App::setLocale($locale);

    return Response::noContent();
})->name('locale.switch');

// START PAGE REDIRECT

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('info');
    }

    return auth()->user()->isSuperAdmin()
        ? redirect()->route('admin.index')
        : redirect()->route('main.index');
});

// PUBLIC PAGE

Route::view('/info', 'pages.info')->name('info');

// AUTHENTICATED

Route::middleware('auth')->group(function () {

    // MAIN

    Route::get('/main/{company}/posts', [PostController::class, 'index'])
        ->name('main.posts.index');

    Route::get('/main/{company}/posts/{post}', [PostController::class, 'show'])
        ->name('main.posts.show');

    Route::get('/main/{company}', [CompanyController::class, 'show'])
        ->name('main.show');

    Route::get('/main', [CompanyController::class, 'index'])
        ->name('main.index');

    // PROFILE

    Route::patch('/dashboard/update', [ProfileController::class, 'update'])
        ->name('dashboard.update');

    Route::delete('/dashboard/destroy', [ProfileController::class, 'destroy'])
        ->name('dashboard.destroy');

    Route::get('/dashboard', [ProfileController::class, 'edit'])
        ->name('dashboard');

    // OTHER PAGES

    Route::view('/forum', 'pages.forum')->name('forum');

    Route::view('/chat', 'pages.chat')->name('chat');

    // SUPERADMIN

    Route::prefix('admin')->middleware(['superadmin'])->group(function () {

        Route::view('/', 'pages.admin')
            ->name('admin.index');

        // USERS

        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('admin.users.create');

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('admin.users.show');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('admin.users.edit');

        // POSTS

        Route::get('/posts', [PostController::class, 'index'])
            ->name('admin.posts.index');

        Route::post('/posts', [PostController::class, 'store'])
            ->name('admin.posts.store');

        Route::get('/posts/{post}', [PostController::class, 'show'])
            ->name('admin.posts.show');

        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
            ->name('admin.posts.edit');

        // COMPANIES

        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('admin.companies.index');

        Route::get('/companies/create', [CompanyController::class, 'create'])
            ->name('admin.companies.create');

        Route::post('/companies', [CompanyController::class, 'store'])
            ->name('admin.companies.store');

        Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])
            ->name('admin.companies.edit');

        Route::get('/companies/{company}/logo', [CompanyController::class, 'logo'])
            ->name('admin.companies.logo');

        Route::get('/companies/{company}', [CompanyController::class, 'show'])
            ->name('admin.companies.show');

    });
});

require __DIR__.'/auth.php';

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

    Route::get('/main', [CompanyController::class, 'index'])
        ->name('main.index');

    Route::get('/main/{company}', [CompanyController::class, 'show'])
        ->name('main.show');

    Route::get('/main/{company}/posts', [PostController::class, 'index'])
        ->name('main.posts.index');

    Route::get('/main/{company}/posts/{post}', [PostController::class, 'show'])
        ->name('main.posts.show');

    // PROFILE

    Route::get('/dashboard', [ProfileController::class, 'edit'])
        ->name('dashboard');

    Route::patch('/dashboard/update', [ProfileController::class, 'update'])
        ->name('dashboard.update');

    Route::delete('/dashboard/destroy', [ProfileController::class, 'destroy'])
        ->name('dashboard.destroy');

    // OTHER PAGES

    Route::view('/forum', 'pages.forum')->name('forum');
    Route::view('/chat', 'pages.chat')->name('chat');

    // ADMIN

    Route::prefix('admin')->group(function () {

        Route::view('/', 'pages.admin')
            ->name('admin.index');

        Route::resource('users', UserController::class)
            ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'])
            ->names('admin.users');

        Route::resource('posts', PostController::class)
            ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'])
            ->names('admin.posts');

        Route::resource('companies', CompanyController::class)
            ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'])
            ->names('admin.companies');

        Route::get('/companies/{company}/logo', [CompanyController::class, 'logo'])
            ->name('admin.companies.logo');

    });
});

require __DIR__.'/auth.php';

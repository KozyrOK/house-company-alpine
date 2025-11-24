<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ProfileController;

Route::post('/locale/{locale}', function ($locale) {
    $available = ['en', 'uk', 'ru'];

    if (!in_array($locale, $available)) {
        $locale = 'en';
    }

    Session::put('locale', $locale);
    App::setLocale($locale);

    return Response::noContent();
})->name('locale.switch');

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('companies.index')
        : redirect()->route('info');
});

// Public pages
Route::view('/info', 'pages.info')->name('info');

// Authenticated users
Route::middleware('auth')->group(function () {

    Route::get('/main', fn() => redirect()->route('companies.index'))->name('main');

    Route::get('/companies', [CompanyController::class, 'index'])
        ->name('companies.index');

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->name('companies.show');

    Route::get('/companies/{company}/posts', [PostController::class, 'index'])
        ->name('companies.posts.index');

    Route::get('/companies/{company}/posts/{post}', [PostController::class, 'show'])
        ->name('companies.posts.show');

    Route::get('/dashboard', [ProfileController::class, 'edit'])
        ->name('dashboard');
    Route::patch('/dashboard/update', [ProfileController::class, 'update'])
        ->name('dashboard.update');
    Route::delete('/dashboard/destroy', [ProfileController::class, 'destroy'])
        ->name('dashboard.destroy');

    Route::view('/forum', 'pages.forum')->name('forum');
    Route::view('/chat', 'pages.chat')->name('chat');

    Route::prefix('superadmin')->group(function () {

        Route::view('/', 'pages.superadmin')
            ->name('main-component.superadmin');

        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('admin.users.show');

        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('admin.companies.index');

        Route::get('/posts', [PostController::class, 'index'])
            ->name('admin.posts.index');
    });
});

require __DIR__.'/auth.php';

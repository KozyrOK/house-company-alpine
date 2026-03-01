<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ProfileController;

use App\Http\Controllers\Web\AdminCompanyController;
use App\Http\Controllers\Web\AdminUserController;
use App\Http\Controllers\Web\AdminPostController;

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

    if (auth()->user()->isSuperAdmin() || auth()->user()->isAdminInAnyCompany()) {
        return redirect()->route('admin.index');
    }
    else {
        return redirect()->route('main.index');
    }
});

// PUBLIC PAGE

Route::view('/info', 'pages.info')->name('info');

// AUTHENTICATED

Route::middleware('auth')->group(function () {

    // MAIN

    Route::get('/main', [MainController::class, 'index'])
        ->name('main.index');

    // COMPANIES

    Route::get('/companies', [CompanyController::class, 'index'])
        ->name('companies.index');

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->name('companies.show');

    // POSTS

    Route::get('/companies/{company}/posts', [PostController::class, 'index'])
        ->name('companies.posts.index');

    Route::get('/posts/{post}', [PostController::class, 'show'])
        ->name('posts.show');

//    Route::get('/main/companies/{company}/posts', [PostController::class, 'index'])
//        ->name('main.posts.index');
//
//    Route::get('/main/companies/{company}/posts/{post}', [PostController::class, 'show'])
//        ->name('main.posts.show');

    // USERS

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show');

//    Route::get('/main/companies/{company}/users', [UserController::class, 'index'])
//        ->name('main.users.index');
//
//    Route::get('/main/companies/{company}/users/{user}', [UserController::class, 'show'])
//        ->name('main.users.show');


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

    Route::prefix('admin')->middleware('admin.access')->name('admin.')->group(function () {

        Route::view('/', 'pages.admin')
            ->name('index');

        // COMPANIES

        Route::get('/companies', [AdminCompanyController::class, 'index'])
            ->name('companies.index');

        Route::get('/companies/create', [AdminCompanyController::class, 'create'])
            ->name('companies.create');

        Route::post('/companies', [AdminCompanyController::class, 'store'])
            ->name('companies.store');

        Route::get('/companies/{company}', [AdminCompanyController::class, 'show'])
            ->name('companies.show');

        Route::get('/companies/{company}/edit', [AdminCompanyController::class, 'edit'])
            ->name('companies.edit');

        Route::patch('/companies/{company}', [AdminCompanyController::class, 'update'])
            ->name('companies.update');

        Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])
            ->name('companies.destroy');

        Route::get('/companies/{company}/logo', [AdminCompanyController::class, 'logo'])
            ->name('companies.logo');

        // USERS

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [AdminUserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}', [AdminUserController::class, 'show'])
            ->name('users.show');

        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');

        Route::patch('/users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        // POSTS

        Route::get('/posts', [AdminPostController::class, 'index'])
            ->name('posts.index');

        Route::get('/posts/create', [AdminPostController::class, 'create'])
            ->name('posts.create');

        Route::post('/posts', [AdminPostController::class, 'store'])
            ->name('posts.store');

        Route::get('/posts/{post}', [AdminPostController::class, 'show'])
            ->name('posts.show');

        Route::get('/posts/{post}/edit', [AdminPostController::class, 'edit'])
            ->name('posts.edit');

        Route::patch('/posts/{post}', [AdminPostController::class, 'update'])
            ->name('posts.update');

        Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])
            ->name('posts.destroy');

    });
});

require __DIR__.'/auth.php';

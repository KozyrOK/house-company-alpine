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
use App\Http\Controllers\Web\MainController;

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

    $user = auth()->user();

    if ($user->isSuperAdmin()) {
        return redirect()->route('admin.index');
    }

    if (!currentCompany()) {
        return redirect()->route('company.select');
    }

    $role = $user->roleIn(currentCompany());

    if (in_array($role, ['admin'], true)) {
        return redirect()->route('admin.index');
    }

    return redirect()->route('main.index');
});

// PUBLIC PAGE

Route::view('/info', 'pages.info')->name('info');

// AUTHENTICATED

Route::middleware('auth')->group(function () {

    Route::get('/company/select', [CompanyController::class, 'select'])
        ->name('company.select');

    Route::post('/companies/{company}/switch', [CompanyController::class, 'switch'])
        ->name('companies.switch');

    Route::get('/company', [CompanyController::class, 'current'])
        ->name('company.current');

    // MAIN

    Route::middleware('main.access')->group(function () {
        Route::get('/main', [MainController::class, 'index'])
        ->name('main.index');

        Route::get('/main/companies', [CompanyController::class, 'index'])
            ->name('main.companies.index');

        Route::get('/main/companies/{company}', [CompanyController::class, 'show'])
            ->name('main.companies.show');

        Route::get('/main/companies/{company}/posts', [PostController::class, 'index'])
            ->name('main.posts.index');

        Route::get('/main/companies/{company}/posts/{post}', [PostController::class, 'show'])
            ->name('main.posts.show');

        Route::get('/main/companies/{company}/users', [UserController::class, 'index'])
            ->name('main.users.index');

        Route::get('/main/companies/{company}/users/{user}', [UserController::class, 'show'])
            ->name('main.users.show');
    });

    // COMPANIES

    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware('admin.access')
        ->name('companies.index');

    Route::get('/companies/trash', [AdminCompanyController::class, 'trash'])
        ->name('companies.trash');

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware('admin.access')
        ->name('companies.show');

    // POSTS

    Route::get('/companies/{company}/posts', [PostController::class, 'index'])
        ->middleware('admin.access')
        ->name('companies.posts.index');

    // USERS

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    // PROFILE

    Route::get('/dashboard', [ProfileController::class, 'edit'])
        ->name('dashboard');

    Route::patch('/dashboard/update', [ProfileController::class, 'update'])
        ->name('dashboard.update');

    Route::delete('/dashboard/destroy', [ProfileController::class, 'destroy'])
        ->name('dashboard.destroy');

    // OTHER PAGES

    Route::view('/chat', 'pages.chat')->name('chat');

    // ADMIN

    Route::prefix('admin')->middleware('admin.access')->name('admin.')->group(function () {

        Route::view('/', 'pages.admin')
            ->name('index');

        // COMPANIES

        Route::get('/companies', [AdminCompanyController::class, 'index'])
            ->name('companies.index');

        Route::get('/companies/trash', [AdminCompanyController::class, 'trash'])
            ->name('companies.trash');

        Route::get('/companies/create', [AdminCompanyController::class, 'create'])
            ->name('companies.create');

        Route::post('/companies', [AdminCompanyController::class, 'store'])
            ->middleware('superadmin.only')
            ->name('companies.store');

        Route::get('/companies/{company}', [AdminCompanyController::class, 'show'])
            ->name('companies.show');

        Route::get('/companies/{company}/edit', [AdminCompanyController::class, 'edit'])
            ->name('companies.edit');

        Route::patch('/companies/{company}', [AdminCompanyController::class, 'update'])
            ->name('companies.update');

        Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])
            ->middleware('superadmin.only')
            ->name('companies.destroy');

        Route::patch('/companies/{company}/restore', [AdminCompanyController::class, 'restore'])
            ->middleware('superadmin.only')
            ->name('companies.restore');

        Route::get('/companies/{company}/logo', [AdminCompanyController::class, 'logo'])
            ->name('companies.logo');

        // USERS

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/trash', [AdminUserController::class, 'trash'])
            ->name('users.trash');

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

        Route::patch('/users/{user}/restore', [AdminUserController::class, 'restore'])
            ->name('users.restore');

        Route::patch('/users/{user}/approve', [AdminUserController::class, 'approve'])
            ->name('users.approve');

        // POSTS

        Route::get('/posts', [AdminPostController::class, 'index'])
            ->name('posts.index');

        Route::get('/posts/trash', [AdminPostController::class, 'trash'])
            ->name('posts.trash');

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

        Route::patch('/posts/{post}/restore', [AdminPostController::class, 'restore'])
            ->name('posts.restore');

        Route::patch('/posts/{post}/approve', [AdminPostController::class, 'approve'])
            ->name('posts.approve');

    });
});

require __DIR__.'/auth.php';

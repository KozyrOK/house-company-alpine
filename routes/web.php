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
use App\Http\Controllers\Web\ActionApproveController;

// LOCALE SWITCHER

Route::post('/locale/{locale}', function (string $locale) {
    $available = ['en', 'uk', 'ru'];

    if (!in_array($locale, $available, true)) {
        $locale = 'en';
    }

    if (auth()->check()) {
        auth()->user()->update(['locale' => $locale]);
    }

    Session::put('locale', $locale);
    App::setLocale($locale);

    return Response::noContent();
})->name('locale.switch');

Route::post('/theme/{theme}', function (string $theme) {
    $available = ['light', 'dark', 'system'];

    if (!in_array($theme, $available, true)) {
        $theme = 'system';
    }

    if (auth()->check()) {
        auth()->user()->update(['theme' => $theme]);
    }

    Session::put('theme', $theme);

    return Response::noContent();
})->name('theme.switch');

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
        $activeCompaniesCount = $user->companies()
            ->where('companies.status_company', 'active')
            ->wherePivotIn('status_membership', ['active', 'pending_admin'])
            ->count();

        return $activeCompaniesCount > 0
            ? redirect()->route('company.select')
            : redirect()->route('dashboard');
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

    Route::post('/company/request-membership', [CompanyController::class, 'requestMembership'])
        ->middleware('verified')
        ->name('company.request-membership');

    Route::post('/company/request-admin', [CompanyController::class, 'requestAdmin'])
        ->middleware('admin.access')
        ->name('company.request-admin');

    // MAIN

    Route::middleware('main.access')->group(function () {
        Route::get('/main', [MainController::class, 'index'])
        ->name('main.index');

        Route::get('/main/companies', [CompanyController::class, 'index'])
            ->name('main.companies.index');

        Route::get('/main/company', [CompanyController::class, 'current'])
            ->name('main.companies.show');

        Route::get('/main/posts', [PostController::class, 'index'])
            ->name('main.posts.index');

        Route::get('/main/posts/create', [PostController::class, 'create'])
            ->name('main.posts.create');

        Route::post('/main/posts', [PostController::class, 'store'])
            ->name('main.posts.store');

        Route::get('/main/posts/trash', [PostController::class, 'trash'])
            ->name('main.posts.trash');

        Route::patch('/main/posts/{post}/restore', [PostController::class, 'restore'])
            ->name('main.posts.restore');

        Route::get('/main/posts/{post}', [PostController::class, 'show'])
            ->name('main.posts.show');

        Route::get('/main/posts/{post}/edit', [PostController::class, 'edit'])
            ->name('main.posts.edit');

        Route::patch('/main/posts/{post}', [PostController::class, 'update'])
            ->name('main.posts.update');

        Route::get('/main/users', [UserController::class, 'index'])
            ->name('main.users.index');

        Route::get('/main/users/{user}', [UserController::class, 'show'])
            ->name('main.users.show');
    });

    // COMPANIES

    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware('admin.access')
        ->name('companies.index');

    Route::get('/companies/trash', [AdminCompanyController::class, 'trash'])
        ->middleware('superadmin.only')
        ->name('companies.trash');

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware('admin.access')
        ->name('companies.show');

    // POSTS

    Route::get('/posts', [PostController::class, 'index'])
        ->middleware('admin.access')
        ->name('posts.index');

    // USERS

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    // PROFILE

    Route::get('/dashboard', [ProfileController::class, 'edit'])
        ->middleware('verified')
        ->name('dashboard');

    Route::patch('/dashboard/update', [ProfileController::class, 'update'])
        ->middleware('verified')
        ->name('dashboard.update');

    Route::delete('/dashboard/destroy', [ProfileController::class, 'destroy'])
        ->name('dashboard.destroy');

    // OTHER PAGES

    Route::view('/chat', 'pages.chat')->name('chat');

    Route::prefix('action-approve')->middleware('admin.access')->name('action-approve.')->group(function () {
        Route::get('/', [ActionApproveController::class, 'index'])->name('index');
        Route::get('/users-approve', [ActionApproveController::class, 'usersApprove'])->name('users-approve');
        Route::get('/users-approve/{user}', [ActionApproveController::class, 'showUser'])->name('users-show');
        Route::patch('/users-approve/{user}/approve', [ActionApproveController::class, 'approveUser'])->name('users-approve-do');
        Route::patch('/users-approve/{user}/reject', [ActionApproveController::class, 'rejectUser'])->name('users-reject-do');
        Route::get('/posts-approve', [ActionApproveController::class, 'postsApprove'])->name('posts-approve');
        Route::get('/posts-approve/{post}', [ActionApproveController::class, 'showPost'])->name('posts-show');
        Route::patch('/posts-approve/{post}/approve', [ActionApproveController::class, 'approvePost'])->name('posts-approve-do');
        Route::patch('/posts-approve/{post}/reject', [ActionApproveController::class, 'rejectPost'])->name('posts-reject-do');
    });

    // ADMIN

    Route::prefix('admin')->middleware('admin.access')->name('admin.')->group(function () {

        Route::view('/', 'pages.admin')
            ->name('index');

        // COMPANIES

        Route::get('/companies', [AdminCompanyController::class, 'index'])
            ->middleware('superadmin.only')
            ->name('companies.index');

        Route::get('/companies/trash', [AdminCompanyController::class, 'trash'])
            ->middleware('superadmin.only')
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

        Route::get('/users/pending', [AdminUserController::class, 'pending'])
            ->name('users.pending');

        Route::get('/users/create', [AdminUserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}', [AdminUserController::class, 'show'])
            ->name('users.show');

        Route::get('/users/{user}/companies', [AdminUserController::class, 'companies'])
            ->middleware('superadmin.only')
            ->name('users.companies');

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

        Route::get('/posts/pending', [AdminPostController::class, 'pending'])
            ->name('posts.pending');

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

<?php
use Illuminate\Support\Facades\Route;

use App\Models\Company;
use App\Models\Post;
use App\Models\User;

use App\Http\Controllers\API\AdminCompanyController as AdminCompanyController;
use App\Http\Controllers\API\AdminUserController as AdminUserController;
use App\Http\Controllers\API\AdminPostController as AdminPostController;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['web', 'auth:sanctum'])->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('company/{company?}/logo', [CompanyController::class, 'logo']);

    // COMPANIES

    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware('api.can:viewAny,' . Company::class);

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware('api.can:view,company');

    // USERS

    Route::post('/companies/{company}/users', [UserController::class, 'store'])
        ->middleware('api.can:create,' . User::class . ',company');

    Route::get('/companies/{company}/users/{user}', [UserController::class, 'show'])
        ->middleware('api.can:view,user');

    Route::patch('/companies/{company}/users/{user}', [UserController::class, 'update'])
        ->middleware('api.can:update,user');

    Route::delete('/companies/{company}/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.can:delete,user');

    Route::patch('/companies/{company}/users/{user}/approve', [UserController::class, 'approve'])
        ->middleware('api.can:approve,user');

    // POSTS

    Route::post('/companies/{company}/posts', [PostController::class, 'store'])
        ->middleware('api.can:create,' . Post::class . ',company');

    Route::get('/companies/{company}/posts/{post}', [PostController::class, 'show'])
        ->middleware('api.can:view,post');

    Route::patch('/companies/{company}/posts/{post}', [PostController::class, 'update'])
        ->middleware('api.can:update,post');

    Route::patch('/companies/{company}/posts/{post}/approve', [PostController::class, 'approve'])
        ->middleware('api.can:approve,post');

    Route::delete('/companies/{company}/posts/{post}', [PostController::class, 'destroy'])
        ->middleware('api.can:delete,post');
});

    // ADMIN

Route::prefix('admin')->middleware(['web', 'auth:sanctum', 'admin.access'])->group(function () {

    // COMPANIES

    Route::get('/companies', [AdminCompanyController::class, 'index'])
        ->middleware('api.can:viewAny,' . Company::class);

    Route::post('/companies', [AdminCompanyController::class, 'store'])
        ->middleware('api.can:create,' . Company::class);

    Route::get('/companies/{company}', [AdminCompanyController::class, 'show'])
        ->middleware('api.can:view,company');

    Route::patch('/companies/{company}', [AdminCompanyController::class, 'update'])
        ->middleware('api.can:update,company');

    Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])
        ->middleware('api.can:delete,company');

    Route::post('/companies/{company}/logo', [AdminCompanyController::class, 'uploadLogo'])
        ->middleware('api.can:update,company');

    Route::delete('/companies/{company}/logo', [AdminCompanyController::class, 'deleteLogo'])
        ->middleware('api.can:update,company');

    // USERS

    Route::get('/users', [AdminUserController::class, 'index'])
        ->middleware('api.can:viewAny,' . User::class);

    Route::get('/users/{user}', [AdminUserController::class, 'show'])
        ->middleware('api.can:view,user');

    Route::patch('/users/{user}', [AdminUserController::class, 'update'])
        ->middleware('api.can:update,user');

    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
        ->middleware('api.can:delete,user');

    // POSTS

    Route::get('/posts', [AdminPostController::class, 'index'])
        ->middleware('api.can:viewAny,' . Post::class);

    Route::get('/posts/{post}', [AdminPostController::class, 'show'])
        ->middleware('api.can:view,post');

    Route::patch('/posts/{post}', [AdminPostController::class, 'update'])
        ->middleware('api.can:update,post');

    Route::patch('/posts/{post}/approve', [AdminPostController::class, 'approve'])
        ->middleware('api.can:approve,post');

    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])
        ->middleware('api.can:delete,post');

});

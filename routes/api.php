<?php

use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
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

    Route::get('/main', [CompanyController::class, 'index'])
    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware('api.can:viewAny,' . Company::class);

    Route::get('/main/{company}', [CompanyController::class, 'show'])
    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware('api.can:view,' . Company::class);

    Route::get('/main/{company}/users', [UserController::class, 'index'])
    Route::get('/companies/{company}/users', [UserController::class, 'index'])
        ->middleware('api.can:viewAny,' . User::class);

    Route::post('/main/{company}/users', [UserController::class, 'store'])
    Route::post('/companies/{company}/users', [UserController::class, 'store'])
        ->middleware('api.can:create,' . User::class);

    Route::get('/main/{company}/users/{user}', [UserController::class, 'show'])
    Route::get('/companies/{company}/users/{user}', [UserController::class, 'show'])
        ->middleware('api.can:view,' . User::class);

    Route::patch('/main/{company}/users/{user}', [UserController::class, 'update'])
    Route::patch('/companies/{company}/users/{user}', [UserController::class, 'update'])
        ->middleware('api.can:update,' . User::class);

    Route::delete('/main/{company}/users/{user}', [UserController::class, 'destroy'])
    Route::delete('/companies/{company}/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.can:delete,' . User::class);

    Route::patch('/main/{company}/users/{user}/approve', [UserController::class, 'approve'])
    Route::patch('/companies/{company}/users/{user}/approve', [UserController::class, 'approve'])
        ->middleware('api.can:approve,' . User::class);

    Route::get('/main/{company}/posts', [PostController::class, 'index'])
    Route::get('/companies/{company}/posts', [PostController::class, 'index'])
        ->middleware('api.can:viewAny,' . Post::class);

    Route::post('/main/{company}/posts', [PostController::class, 'store'])
    Route::post('/companies/{company}/posts', [PostController::class, 'store'])
        ->middleware('api.can:create,' . Post::class);

    Route::get('/main/{company}/posts/{post}', [PostController::class, 'show'])
    Route::get('/companies/{company}/posts/{post}', [PostController::class, 'show'])
        ->middleware('api.can:view,' . Post::class);

    Route::patch('/main/{company}/posts/{post}', [PostController::class, 'update'])
    Route::patch('/companies/{company}/posts/{post}', [PostController::class, 'update'])
        ->middleware('api.can:update,' . Post::class);

    Route::patch('/main/{company}/posts/{post}/approve', [PostController::class, 'approve'])
    Route::patch('/companies/{company}/posts/{post}/approve', [PostController::class, 'approve'])
        ->middleware('api.can:approve,' . Post::class);

    Route::delete('/main/{company}/posts/{post}', [PostController::class, 'destroy'])
    Route::delete('/companies/{company}/posts/{post}', [PostController::class, 'destroy'])
        ->middleware('api.can:delete,' . Post::class);
});

Route::prefix('superadmin')->middleware(['web', 'auth:sanctum'])->group(function () {

    Route::get('/admin', [CompanyController::class, 'index'])
        ->middleware('api.can:viewAny,' . Company::class);

    Route::post('/admin', [CompanyController::class, 'store'])
        ->middleware('api.can:create,' . Company::class);

    Route::get('/admin/{company}', [CompanyController::class, 'show'])
        ->middleware('api.can:view,' . Company::class);

    Route::patch('/admin/{company}', [CompanyController::class, 'update'])
        ->middleware('api.can:update,' . Company::class);

    Route::delete('/admin/{company}', [CompanyController::class, 'destroy'])
        ->middleware('api.can:delete,' . Company::class);

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('api.can:viewAny,' . User::class);

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('api.can:view,' . User::class);

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.can:delete,' . User::class);

    Route::get('/posts', [PostController::class, 'index'])
        ->middleware('api.can:viewAny,' . Post::class);

    Route::get('/posts/{post}', [PostController::class, 'show'])
        ->middleware('api.can:view,' . Post::class);

    Route::patch('/posts/{post}/approve', [PostController::class, 'approve'])
        ->middleware('api.can:approve,' . Post::class);

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->middleware('api.can:delete,' . Post::class);
});

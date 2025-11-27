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

    // COMPANIES

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware('api.can:view,' . Company::class);

    // USERS

    Route::get('/companies/{company}/users', [UserController::class, 'index'])
        ->middleware('api.can:viewAny,' . User::class);

    Route::post('/companies/{company}/users', [UserController::class, 'store'])
        ->middleware('api.can:create,' . User::class);

    Route::get('/companies/{company}/users/{user}', [UserController::class, 'show'])
        ->middleware('api.can:view,' . User::class);

    Route::patch('/companies/{company}/users/{user}', [UserController::class, 'update'])
        ->middleware('api.can:update,' . User::class);

    Route::delete('/companies/{company}/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.can:delete,' . User::class);

    Route::patch('/companies/{company}/users/{user}/approve', [UserController::class, 'approve'])
        ->middleware('api.can:approve,' . User::class);

    // POSTS

    Route::get('/companies/{company}/posts', [PostController::class, 'index'])
        ->middleware('api.can:viewAny,' . Post::class);

    Route::post('/companies/{company}/posts', [PostController::class, 'store'])
        ->middleware('api.can:create,' . Post::class);

    Route::get('/companies/{company}/posts/{post}', [PostController::class, 'show'])
        ->middleware('api.can:view,' . Post::class);

    Route::patch('/companies/{company}/posts/{post}', [PostController::class, 'update'])
        ->middleware('api.can:update,' . Post::class);

    Route::patch('/companies/{company}/posts/{post}/approve', [PostController::class, 'approve'])
        ->middleware('api.can:approve,' . Post::class);

    Route::delete('/companies/{company}/posts/{post}', [PostController::class, 'destroy'])
        ->middleware('api.can:delete,' . Post::class);
});

    // SUPERADMIN

Route::prefix('admin')->middleware(['web', 'auth:sanctum'])->group(function () {

    Route::get('/', [CompanyController::class, 'index'])
        ->middleware('api.can:viewAny,' . Company::class);

    Route::post('/', [CompanyController::class, 'store'])
        ->middleware('api.can:create,' . Company::class);

    Route::get('/{company}', [CompanyController::class, 'show'])
        ->middleware('api.can:view,' . Company::class);

    Route::patch('/{company}', [CompanyController::class, 'update'])
        ->middleware('api.can:update,' . Company::class);

    Route::delete('/{company}', [CompanyController::class, 'destroy'])
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

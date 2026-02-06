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

    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware('api.can:viewAny,' . Company::class);

    // USERS

    Route::post('/companies/{company}/users', [UserController::class, 'store'])
        ->middleware('api.can:create,' . User::class . ',company');

    Route::get('/companies/{company}/users/{user}', [UserController::class, 'show'])
        ->middleware('api.can:view,' . User::class . ',user');

    Route::patch('/companies/{company}/users/{user}', [UserController::class, 'update'])
        ->middleware('api.can:update,' . User::class . ',user');

    Route::delete('/companies/{company}/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.can:delete,' . User::class . ',user');

    Route::patch('/companies/{company}/users/{user}/approve', [UserController::class, 'approve'])
        ->middleware('api.can:approve,' . User::class . ',user');

    // POSTS

    Route::post('/companies/{company}/posts', [PostController::class, 'store'])
        ->middleware('api.can:create,' . Post::class . ',company');

    Route::get('/companies/{company}/posts/{post}', [PostController::class, 'show'])
        ->middleware('api.can:view,' . Post::class . ',post');

    Route::patch('/companies/{company}/posts/{post}', [PostController::class, 'update'])
        ->middleware('api.can:update,' . Post::class . ',post');

    Route::patch('/companies/{company}/posts/{post}/approve', [PostController::class, 'approve'])
        ->middleware('api.can:approve,' . Post::class . ',post');

    Route::delete('/companies/{company}/posts/{post}', [PostController::class, 'destroy'])
        ->middleware('api.can:delete,' . Post::class . ',post');
});

    // ADMIN

Route::prefix('admin')->middleware(['web', 'auth:sanctum'])->group(function () {

    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware('api.can:viewAny,' . Company::class);

    Route::post('/companies', [CompanyController::class, 'store'])
        ->middleware('api.can:create,' . Company::class);

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('api.can:viewAny,' . User::class);

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('api.can:view,' . User::class);

    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->middleware('api.can:update,' . User::class);

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('api.can:delete,' . User::class);

    Route::get('/posts', [PostController::class, 'index'])
        ->middleware('api.can:viewAny,' . User::class);

    Route::get('/posts/{post}', [PostController::class, 'show'])
        ->middleware('api.can:view,' . Post::class);

    Route::patch('/posts/{post}', [PostController::class, 'update'])
        ->middleware('api.can:update,' . Post::class);

    Route::patch('/posts/{post}/approve', [PostController::class, 'approve'])
        ->middleware('api.can:approve,' . Post::class);

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->middleware('api.can:delete,' . Post::class);

    Route::post('/companies/{company}/logo', [CompanyController::class, 'uploadLogo'])
        ->middleware('api.can:update,' . Company::class);

    Route::delete('/companies/{company}/logo', [CompanyController::class, 'deleteLogo'])
        ->middleware('api.can:update,' . Company::class);

    Route::patch('/companies/{company}', [CompanyController::class, 'update'])
        ->middleware('api.can:update,' . Company::class);

    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])
        ->middleware('api.can:delete,' . Company::class);

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware('api.can:view,' . Company::class);

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['web','auth:sanctum'])->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
//    Route::post('/logout-all', [AuthController::class, 'logoutAll']); logout from all devices

    Route::get('company/{companyId?}/logo', [CompanyController::class, 'logo']);

    Route::apiResource('companies', CompanyController::class);

    Route::apiResource('companies.users', UserController::class);
    Route::apiResource('companies.posts', PostController::class)
        ->parameters(['companies' => 'companyId']);

    Route::patch('/companies/{company}/users/{user}/approve', [UserController::class, 'approve'])
        ->name('companies.users.approve');
    Route::patch('/companies/{company}/posts/{post}/approve', [PostController::class, 'approve'])
        ->name('companies.posts.approve');
});

Route::prefix('superadmin')->middleware(['web', 'auth:sanctum'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('companies', CompanyController::class);
});

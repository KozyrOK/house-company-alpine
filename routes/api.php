<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\PostController;

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {

    // Public routes (don`t require authorization)
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);

    // Authorized routes (Sanctum)
    Route::middleware('role:superadmin')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // Common to all authorized
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);

    // USERS

    // role:superadmin - full access
    Route::middleware('role:superadmin')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // role:admin - limited access (filtering in controller)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class)->except(['destroy']);
    });

    // company_head - limited access (only own company)
    Route::middleware('role:company_head')->group(function () {
        Route::get('/company/users', [CompanyController::class, 'users']);
        Route::post('/company/users/{user}/attach', [CompanyController::class, 'attachUser']);
        Route::delete('/company/users/{user}/detach', [CompanyController::class, 'detachUser']);
    });

    // COMPANIES

    // superadmin + admin -> full access
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::apiResource('companies', CompanyController::class);
    });

    // company_head -> limited access (only own company)
    Route::middleware('role:company_head')->group(function () {
        Route::get('/company', [CompanyController::class, 'myCompany']);
        Route::put('/company', [CompanyController::class, 'updateMyCompany']);
    });

    // POSTS

    // superadmin/admin -> full access
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::apiResource('posts', PostController::class);
        Route::post('/posts/{post}/approve', [PostController::class, 'approve']);
    });

    // company_head -> limited access (only own company)
    Route::middleware('role:company_head')->group(function () {
        Route::get('/company/posts', [PostController::class, 'companyPosts']);
        Route::post('/company/posts/{post}/approve', [PostController::class, 'approve']);
    });

    // user -> limited access (only own posts)
    Route::middleware('role:user')->group(function () {
        Route::get('/my-posts', [PostController::class, 'myPosts']);
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

});

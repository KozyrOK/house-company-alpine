<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\PostController;

Route::middleware('api')->group(function () {
    // Публичные маршруты
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Защищенные маршруты
    Route::middleware(['auth:sanctum'])->group(function () {
        // Ресурсы
        Route::apiResource('users', UserController::class)->middleware('role:superadmin');
        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('posts', PostController::class);

        // Кастомные маршруты
        Route::get('/users/role/{role}', [UserController::class, 'byRole']);
    });
});

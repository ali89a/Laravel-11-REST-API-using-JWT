<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::get('profile', [AuthController::class, 'me'])->middleware('auth:api');
        Route::get('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('users', [UserController::class, 'index']);
    });

});

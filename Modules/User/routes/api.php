<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\AuthController;
use Modules\User\App\Http\Controllers\UserController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'store']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::apiResource('users', UserController::class)->except(['updateUser', 'store', 'destroy']);
    Route::put('user', [UserController::class, 'update']);
    Route::delete('user', [UserController::class, 'destroy']);
});

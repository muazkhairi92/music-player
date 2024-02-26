<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\App\Http\Controllers\SubscriptionController;
use Modules\Subscription\App\Http\Controllers\TransactionController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::post('deposit', [TransactionController::class, 'generatePaymentUrl']);
    Route::get('home', [SubscriptionController::class, 'home']);
    Route::get('transactions', [SubscriptionController::class, 'index']);
});

Route::post('callback', [TransactionController::class, 'callback']);
Route::get('redirect', [TransactionController::class, 'redirect']);
Route::post('redirect', [TransactionController::class, 'redirect']);

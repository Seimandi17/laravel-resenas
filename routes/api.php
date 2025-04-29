<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\WhatsappWebhookController;
use Illuminate\Support\Facades\Http;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/business-public', [BusinessController::class, 'storePublic']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/business', [BusinessController::class, 'show']);
    Route::post('/business', [BusinessController::class, 'store']);
    Route::post('/business/assign', [BusinessController::class, 'assignToUser']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/subscription', [SubscriptionController::class, 'store']);
    Route::get('/subscription', [SubscriptionController::class, 'show']);
    Route::get('/payments', [SubscriptionController::class, 'payments']);
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/actions', [DashboardController::class, 'getRecentActions']);
    Route::get('/dashboard/stats-extended', [DashboardController::class, 'getExtendedStats']);
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/ultramsg/webhook', [WhatsappWebhookController::class, 'handle']);
});

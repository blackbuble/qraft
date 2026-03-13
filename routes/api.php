<?php

use App\Http\Controllers\Api\AiElementController;
use App\Http\Controllers\Api\InspectorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/webhooks/inspector', [InspectorController::class, 'handleWebhook']);

// Stripe Webhooks
Route::post('/webhooks/stripe', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook']);

// AI-Powered Element Discovery
Route::post('/ai/find-element', [AiElementController::class, 'findElement']);
Route::post('/ai/heal-selector', [AiElementController::class, 'healSelector']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InspectorController;
use App\Http\Controllers\Api\AiElementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/webhooks/inspector', [InspectorController::class, 'handleWebhook']);

// AI-Powered Element Discovery
Route::post('/ai/find-element', [AiElementController::class, 'findElement']);
Route::post('/ai/heal-selector', [AiElementController::class, 'healSelector']);

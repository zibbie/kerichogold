<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');

// Cart Routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::put('/item/{id}', [CartController::class, 'updateItem']);
    Route::delete('/item/{id}', [CartController::class, 'removeItem']);
    Route::delete('/clear', [CartController::class, 'clear']);
    Route::post('/validate', [CartController::class, 'validateCart']);
});

// Payment Routes
Route::prefix('payment')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiate']);
    Route::post('/webhook', [PaymentController::class, 'webhook']);
    Route::get('/status/{id}', [PaymentController::class, 'status']);
});

// Web Model Context Protocol (WebMCP)
Route::post('/mcp', [\App\Http\Controllers\McpController::class, 'handle']);

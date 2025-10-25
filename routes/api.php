<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::apiResource('posts', PostController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class)->except(['index', 'show']);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API OK']);
});

use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
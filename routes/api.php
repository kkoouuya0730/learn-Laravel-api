<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::apiResource('posts', PostController::class);

Route::get('/test', function () {
    return response()->json(['message' => 'API OK']);
});
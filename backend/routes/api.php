<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CryptoController;
use App\Http\Controllers\Api\WatchlistController;

use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/coins/markets', [CryptoController::class, 'index']);
Route::get('/coins/{id}', [CryptoController::class, 'show']);
Route::get('/coins/{id}/chart', [CryptoController::class, 'chart']);
Route::get('/global', [CryptoController::class, 'global']);
Route::get('/trending', [CryptoController::class, 'trending']);
Route::get('/exchanges', [CryptoController::class, 'exchanges']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist', [WatchlistController::class, 'store']);
    Route::delete('/watchlist/{coin_id}', [WatchlistController::class, 'destroy']);
});

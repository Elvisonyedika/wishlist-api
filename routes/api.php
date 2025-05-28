<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'add']);
    Route::put('/wishlist', [WishlistController::class, 'remove']);
    Route::delete('/wishlist', [WishlistController::class, 'clear']);
    Route::get('/wishlist', [WishlistController::class, 'view']);
});

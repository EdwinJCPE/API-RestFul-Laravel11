<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Transaction\TransactionController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// 

/**
 * Buyers
 */
// Route::resource('buyers', 'App\Http\Controllers\Buyer\BuyerController');
// Route::resource('buyers', App\Http\Controllers\Buyer\BuyerController::class);
// Route::resource('buyers', BuyerController::class, [ 'only' => ['index', 'show']]);
// Route::apiResource('buyers', BuyerController::class)->only(['index', 'show']);
Route::apiResource('buyers', BuyerController::class, ['only' => ['index', 'show']]);

/**
 * Categories
 */
// Route::resource('buyers', CategoryController::class, [ 'except' => ['create', 'edit']]);
Route::apiResource('categories', CategoryController::class);

/**
 * Products
 */
Route::apiResource('products', ProductController::class, ['only' => ['index', 'show']]);

/**
 * Transactions
 */
Route::apiResource('transactions', TransactionController::class, ['only' => ['index', 'show']]);

/**
 * Sellers
 */
Route::apiResource('sellers', SellerController::class, ['only' => ['index', 'show']]);

/**
 * Users
 */
// Route::resource('users', UserController::class, [ 'except' => ['create', 'edit']]);
Route::apiResource('users', UserController::class);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Buyer\BuyerSellerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Buyer\BuyerProductController;
use App\Http\Controllers\Buyer\BuyerCategoryController;
use App\Http\Controllers\Buyer\BuyerTransactionController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\Category\CategoryTransactionController;
use App\Http\Controllers\Transaction\TransactionSellerController;
use App\Http\Controllers\Transaction\TransactionCategoryController;

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
Route::apiResource('buyers.sellers', BuyerSellerController::class, ['only' => ['index']]);
Route::apiResource('buyers.products', BuyerProductController::class, ['only' => ['index']]);
Route::apiResource('buyers.categories', BuyerCategoryController::class, ['only' => ['index']]);
Route::apiResource('buyers.transactions', BuyerTransactionController::class, ['only' => ['index']]);

/**
 * Categories
 */
// Route::resource('buyers', CategoryController::class, [ 'except' => ['create', 'edit']]);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('categories.buyers', CategoryBuyerController::class, ['only' => ['index']]);
Route::apiResource('categories.sellers', CategorySellerController::class, ['only' => ['index']]);
Route::apiResource('categories.products', CategoryProductController::class, ['only' => ['index']]);
Route::apiResource('categories.transactions', CategoryTransactionController::class, ['only' => ['index']]);

/**
 * Products
 */
Route::apiResource('products', ProductController::class, ['only' => ['index', 'show']]);

/**
 * Transactions
 */
Route::apiResource('transactions', TransactionController::class, ['only' => ['index', 'show']]);
Route::apiResource('transactions.sellers', TransactionSellerController::class, ['only' => ['index']]);
Route::apiResource('transactions.categories', TransactionCategoryController::class, ['only' => ['index']]);

/**
 * Sellers
 */
Route::apiResource('sellers', SellerController::class, ['only' => ['index', 'show']]);

/**
 * Users
 */
// Route::resource('users', UserController::class, [ 'except' => ['create', 'edit']]);
Route::apiResource('users', UserController::class);

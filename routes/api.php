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
use App\Http\Controllers\Seller\SellerBuyerController;
use App\Http\Controllers\Buyer\BuyerCategoryController;
use App\Http\Controllers\Product\ProductBuyerController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Buyer\BuyerTransactionController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\Seller\SellerTransactionController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Product\ProductTransactionController;
use App\Http\Controllers\Category\CategoryTransactionController;
use App\Http\Controllers\Transaction\TransactionSellerController;
use App\Http\Controllers\Product\ProductBuyerTransactionController;
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
Route::apiResource('products.buyers', ProductBuyerController::class, ['only' => ['index']]);
// Route::apiResource('products.categories', ProductCategoryController::class, [ 'only' => ['index', 'update', 'destroy']]);
Route::apiResource('products.categories', ProductCategoryController::class, ['except' => ['store', 'show']]);
Route::apiResource('products.transactions', ProductTransactionController::class, ['only' => ['index']]);
Route::apiResource('products.buyers.transactions', ProductBuyerTransactionController::class, ['only' => ['store']]);

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
Route::apiResource('sellers.buyers', SellerBuyerController::class, ['only' => ['index']]);
Route::apiResource('sellers.products', SellerProductController::class, ['except' => ['show']]);
Route::apiResource('sellers.categories', SellerCategoryController::class, ['only' => ['index']]);
Route::apiResource('sellers.transactions', SellerTransactionController::class, ['only' => ['index']]);

/**
 * Users
 */
Route::get('users/me', [UserController::class, 'me'])->name('me');
// Route::resource('users', UserController::class, [ 'except' => ['create', 'edit']]);
Route::apiResource('users', UserController::class);
// Route::name('verify')->get('users/verify/{token}', 'User\UserController@verify'); // Rutas Fluidas - En Laravel 7<
// Route::get('users/verify/{token}', 'User\UserController@verify')->name('verify'); // En Laravel 7<
// Route::get('users/verify/{token}', 'App\Http\Controllers\User\UserController@verify')->name('verify');
// Route::get('users/verify/{token}', ['App\Http\Controllers\User\UserController', 'verify'])->name('verify');
Route::get('users/verify/{token}', [UserController::class, 'verify'])->name('verify');
Route::get('users/{user}/resend', [UserController::class, 'resend'])->name('resend');

// Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token');
Route::post('oauth/token', [AccessTokenController::class, 'issueToken'])->name('passport.token');

// Route::middleware([\App\Http\Middleware\CustomThrottleRequests::class])->get('/prueba', function () {
//     return response()->json(['message' => 'Ruta protegida']);
// });

// Cuando las rutas del grupo api son configuradas dinámicamente en el archivo app.php es necesario inspeccionar en tiempo de ejecución
// Listar Rutas con Middleware
Route::get('/routes-with-middleware', function () {
    return collect(Route::getRoutes())->map(function ($route) {
        return [
        	'method' => implode('|', $route->methods()), // Métodos HTTP
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => $route->middleware(),
            // 'middleware' => implode(', ', $route->middleware()),
        ];
    });
});

// Agregar Filtros por Middleware
Route::get('/routes-with-specific-middleware', function () {
    return collect(Route::getRoutes())
        ->filter(fn($route) => in_array('api', $route->middleware())) // Filtra rutas con middleware 'api'
        ->map(fn($route) => [
        	'method' => implode('|', $route->methods()), // Métodos HTTP
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => $route->middleware(),
        ]);
});

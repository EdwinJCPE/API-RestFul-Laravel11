<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes([
    'register' => false,
]); // Illuminate\Support\Facades\Auth;

// // Login Routes...
// Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');

// // Logout Routes...
// Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// // Registration Routes...
// Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');

// // Password Reset Routes...
// Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');

// // Password Confirmation Routes...
// Route::get('password/confirm', 'App\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
// Route::post('password/confirm', 'App\Http\Controllers\Auth\ConfirmPasswordController@confirm');

// // Email Verification Routes...
// Route::get('email/verify', 'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
// Route::post('email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

Route::get('home/my-tokens', [App\Http\Controllers\HomeController::class, 'getTokens'])->name('personal-tokens');
Route::get('home/my-clients', [App\Http\Controllers\HomeController::class, 'getClients'])->name('personal-clients');
Route::get('home/authorized-clients', [App\Http\Controllers\HomeController::class, 'getAuthorizedClients'])->name('authorized-clients');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

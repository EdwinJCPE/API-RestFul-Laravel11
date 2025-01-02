<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Una de las formas recomendadas de usar eventos es directamente en los modelos usando funciones anónimas - Laravel 7+
        // User::created(function (User $user) {
        //     // Mail::to($user->email)->send(new UserCreated($user));
        //     // Mail::to($user)->send(new UserCreated($user));

        //     retry(5, function() use ($user) { // Si el envío de correo falla Laravel lo va intentar 5 veces cada 100 ms
        //         Mail::to($user)->send(new UserCreated($user));
        //     }, 100);
        // }); 
        // 
        // User::updated(function ($user) {
        //     if ($user->isDirty('email')) {
        //         retry(5, function() use ($user) {
        //             Mail::to($user)->send(new UserMailChanged($user));
        //         }, 100);
        //     }
        // });
        
        // Product::updated(function (Product $product) {
        //     if ($product->quantity == 0 && $product->estaDisponible()) {
        //         $product->status = Product::PRODUCTO_NO_DISPONIBLE;

        //         $product->save();
        //     }
        // });
    }
}

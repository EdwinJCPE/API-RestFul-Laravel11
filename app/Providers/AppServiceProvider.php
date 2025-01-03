<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

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
        // $this->configureRateLimiting();

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

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

    /**
     * Configure the rate limiters for the application. - Agregado por Edwin JC
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // En Laravel 10.x
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

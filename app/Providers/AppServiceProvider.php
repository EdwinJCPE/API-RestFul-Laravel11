<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Mail\UserCreated;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Mail\UserMailChanged;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Middleware\CustomThrottleRequests;

class AppServiceProvider extends ServiceProvider
{
    use ApiResponser;

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
        // Passport::routes(); // Laravel <=10
        // Passport::tokensExpireIn(Carbon::now()->addSecond(30)); // Tokens de acceso expiran en 30 segundos
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30)); // Tokens de acceso expiran en 30 minutos
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); // Refresh tokens expiran en 30 días
        Passport::enablePasswordGrant(); // Habilitar el Password Grant

        // $this->configureRateLimiting();
        // 

        // Reemplazar globalmente el alias 'throttle' para que apunte a CustomThrottleRequests
        $this->app->router->aliasMiddleware('throttle', CustomThrottleRequests::class);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        

        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
        //         ->response(function (Request $request, array $headers) {
                
        //         // return response('Custom response...', 429, $headers);
        //         return $this->errorResponse('Has excedido el límite de solicitudes. Intenta más tarde.', 429);
        //     });
        // });


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

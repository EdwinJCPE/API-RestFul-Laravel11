<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    public function handle(Request $request, Closure $next, $header = 'X-Name'): Response
    {
        // return $next($request);
        $response = $next($request);

        $response->headers->set($header, config('app.name'));

        return $response;
    }
}

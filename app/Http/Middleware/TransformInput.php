<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $transformer): Response
    {
        // return $next($request);
        /**
         * $request->all()
                Este método es proporcionado directamente por la clase Illuminate\Http\Request.
                Recupera todos los datos de la solicitud (incluidos los datos enviados como parámetros de consulta, en el cuerpo de la solicitud, y archivos).
            $request->request->all()
                Este método accede a la propiedad request de la clase Symfony\Component\HttpFoundation\Request, de la cual la clase de Laravel hereda.
                Recupera únicamente los datos enviados en el cuerpo de la solicitud HTTP (POST).
         */
        $transformedInput = [];

        foreach ($request->request->all() as $input => $value) {
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformedInput);

        return $next($request);
    }
}

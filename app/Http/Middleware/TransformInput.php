<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

        $request->replace($transformedInput); // Reemplazar en la petición original por lo que tenemos actualmente

        // Transformando las Respuestas
        // Realizar las modificaciones de la respueta antes de retornarla
        $response = $next($request);
        // dd($response);

        //Verificamos si es una respuesta de error (Si esta respuesta de error tiene una excepción entonces es una respuesta de error) y el caso especificio de excepción que es ValidationException
        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData(); // Obtener los datos de la respuesta

            $transformedErrors = [];

            foreach ($data->error as $field => $error) {
                $transformedField = $transformer::transformedAttribute($field);
                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
            }

            $data->error = $transformedErrors; // Sustituir los datos error de la respuesta

            $response->setData($data);  // Establecer los nuevos datos para la respuesta
        }
        
        return $response;
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    // protected function errorResponse($message, $code)
    public function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        // return $this->successResponse(['data' => $collection], $code);
        // 
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        // $transformer = $collection->first()->transformer;
        $transformer = $this->getTransformer($collection->first());

        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->transformData($collection, $transformer);

        // return $this->successResponse(['data' => $collection], $code);
        return $this->successResponse($collection, $code);

    } 

    protected function showOne(Model $instance, $code = 200)
    {
        // return $this->successResponse(['data' => $instance], $code);
        // 
        // $transformer = $instance->transformer;
        $transformer = $this->getTransformer($instance);
        $instance = $this->transformData($instance, $transformer);

        // return $this->successResponse(['data' => $instance], $code);
        return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function filterData(Collection $collection, $transformer)
    {
        // Ejemplo: http://apirestful.test/users?esVerificado=1
        // http://apirestful.test/users?esVerificado=1&esAdministrador=true
        foreach (request()->query() as $query => $value) { // recorrer todos los parámetros de la URL
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }

    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {  // Verificamos si en la petición existe el valor sort_by
            // $attribute = request()->sort_by;
            $attribute = $transformer::originalAttribute(request()->sort_by);

            // $collection = $collection->sortBy($attribute);
            $collection = $collection->sortBy->{$attribute};
        }

        return $collection;
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    private function getTransformer($model)
    {
        $transformer = $model->transformer ?? null;

        if (!$transformer) {
            throw new HttpException(500, 'No se puedo determinar el transformador.');
        }

        return $transformer;
    }
}

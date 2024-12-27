<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        // Obtener la lista de compradores de una categoría específica
        $buyers = $category->products()
            ->whereHas('transactions') // Obtiene los productos que al menos están asociadas a una transacción
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions') // Obtener únicamente el indice transactions
            ->collapse() // Como un producto puede tener múltiples transacciones estaríamos obteniendo una serie de colecciones, una lista de transacciones, seguidas de otras, seguida de otra; puesto que queremos una lista utilizamos collapse
            ->pluck('buyer')
            ->unique('id')
            ->values();

        // dd($buyers);
        return $this->showAll($buyers);
    }
}

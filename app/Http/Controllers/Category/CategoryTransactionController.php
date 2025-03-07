<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $this->allowedAdminAction();

        // Obtener la lista de transacciones de una categoría específica
        $transactions = $category->products()
            // ->has('transactions')
            ->whereHas('transactions') // Obtiene los productos que al menos están asociadas a una transacción
            ->with('transactions')
            ->get()
            ->pluck('transactions') // Obtener únicamente el indice transactions
            ->collapse(); // Como un producto puede tener múltiples transacciones estaríamos obteniendo una serie de colecciones, una lista de transacciones, seguidas de otras, seguida de otra; puesto que queremos una lista utilizamos collapse

        // dd($transactions);
        return $this->showAll($transactions);
    }
}

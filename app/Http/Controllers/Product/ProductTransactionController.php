<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        // Obtener la lista de las transacciones de un producto 
        $transactions = $product->transactions;

        return $this->showAll($transactions);
    }
}

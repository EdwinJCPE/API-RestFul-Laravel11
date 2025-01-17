<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        // Obtener la lista de transacciones de un producto específico
        $transactions = $product->transactions;

        return $this->showAll($transactions);
    }
}

<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // public function index(string $id)
    public function index(Seller $seller)
    {
        // Obtener la lista de transacciones de un vendedor
        // $seller = Seller::findOrFail($id);
        // dd($seller);
        // $transactions = $seller->products()->whereHas('transactions')->with('transactions')->get(); // //Se obtiene una lista de productos, cada una de ellas en su interior con sus transacciones.
        $transactions = $seller->products()
            ->whereHas('transactions') // Obtiene los productos que al menos están asociadas a una transacción
            ->with('transactions')
            ->get()
            ->pluck('transactions') // Obtener únicamente el indice transactions
            ->collapse();

        // dd($transactions);
        return $this->showAll($transactions);
    }
}

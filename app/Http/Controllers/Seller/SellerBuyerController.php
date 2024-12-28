<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        // Obtener la lista de compradores de un vendedor específico
        $buyers = $seller->products()
            ->whereHas('transactions') // Los productos que se obtienen son aquellos que al menos están asociadas a una transacción
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();

        // dd($buyers);
        return $this->showAll($buyers);
    }
}

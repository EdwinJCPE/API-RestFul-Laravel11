<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductBuyerController extends ApiController
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
        $this->allowedAdminAction();

        // Obtener la lista de compradores de un producto especifico
        // $buyers = $product->transactions()->with('buyer')->get(); // Se obtiene una lista de transacciones, cada una de ellas en su interior con su buyer (comprador)
        //
        $buyers = $product->transactions()
            ->with('buyer')
            ->get()
            ->pluck('buyer') // Obtener únicamente el indice buyer
            ->unique('id') // Los valores incluidos en la colección sean unicos de acuerdo al id
            ->values(); // Reorganizar los indices en el orden correcto eliminando aquellos que estan vacios

        // dd($buyers);
        return $this->showAll($buyers);
    }
}

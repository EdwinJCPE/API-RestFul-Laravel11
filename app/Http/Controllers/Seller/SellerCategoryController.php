<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        // Obtener la lista de categorías de productos de un vendedor
        //
        // $categories = $seller->products->categories; //Lo que sucede aquí es que se esta obteniendo una lista de productos y Laravel automáticamente lo convierte en una collection, al ser una collection ya deja de ser un products como tal por ende no se puede acceder de manera directa a las categorías de la misma. Para esto Laravel tiene algo conocido como Eager loading

        // $categories = $seller->products()->with('categories')->get(); //Se obtiene una lista de productos, cada una de ellas en su interior con su categoría
        // dd($categories);
        //
        $categories = $seller->products()
            ->with('categories')
            ->get()
            ->pluck('categories')
            ->collapse()
            // ->unique()
            ->unique('id')
            ->values();

            // dd($categories);
        return $this->showAll($categories);
    }
}

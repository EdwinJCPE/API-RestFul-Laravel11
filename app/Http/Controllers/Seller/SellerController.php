<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

// class SellerController extends Controller
class SellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('show');
        $this->middleware('can:view,seller')->only('show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $vendedores = Seller::has('products')->get();
        $vendedores = Seller::all(); // Listará todos los Seller haciendo uso del global scope
        // $vendedores = Seller::orderBy('id', 'asc')->get(); // Listará todos los Seller haciendo uso del global scope

        // return response()->json(['data' => $vendedores], 200);
        return $this->showAll($vendedores);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    public function show(Seller $seller)
    {
        // $vendedor = Seller::has('products')->findOrFail($id);
        //
        // $vendedor = Seller::findOrFail($id); // Hacemos uso del global Scope

        // return response()->json(['data' => $vendedor], 200);
        // return $this->showOne($vendedor);
        return $this->showOne($seller);
    }
}

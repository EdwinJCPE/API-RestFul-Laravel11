<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        // Obtener los productos de un vendedor específico
        $products = $seller->products;

        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
        ]; 

        // $rules = [
        //     'name' => ['required'],
        //     'description' => ['required'],
        //     'quantity' => ['required', 'integer', 'min:1'],
        //     'image' => ['required', 'image'],
        // ];
        // 
        // $this->validate($request, $rules); // En Laravel 10<
        // request()->validate($rules);
        $request->validate($rules);

        $data = $request->all();
        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = '1.jpg';
        $data['seller_id'] = $seller->id;

        // dd($data);
        $product = Product::create($data);

        // 2da Forma
        // $product = Product::create([
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'quantity' => $request->quantity,
        //     'status' => Product::PRODUCTO_NO_DISPONIBLE,
        //     'image' => '1.jpg',
        //     'seller_id' => $seller->id,
        // ]);

        // 3ra Forma
        // $product = Product::create($request->only(['name', 'description', 'quantity']) + 
        //     [
        //         'status' => Product::PRODUCTO_NO_DISPONIBLE,
        //         'image' => '1.jpg',
        //         'seller_id' => $seller->id,
        //     ]
        // );

        return $this->showOne($product, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        //
    }
}

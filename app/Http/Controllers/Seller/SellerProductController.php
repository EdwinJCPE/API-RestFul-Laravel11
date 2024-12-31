<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        // $data['image'] = '1.jpg';
        // $data['image'] = $request->image->store(''); // Si el sistema de archivos por defecto es images (FILESYSTEM_DISK=images)
        $data['image'] = $request->image->store('', 'images');
        // $data['image'] = $request->image->store('products', 'images');
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        // $rules = [
        //     'quantity' => 'integer|min:1',
        //     'status' => 'in:' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE,
        //     'image' => 'image',
        // ];

        $rules = [
            'quantity' => ['integer', 'min:1'],
            'status' => ['in:' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE],
            'image' => ['image'], // Comentado para enviar en formato Json desde Postman
        ];

        $request->validate($rules);

        // if ($seller->id != $product->seller_id) {
        //     return $this->errorResponse('El vendedor especificado no es el vendedor real del producto.', 422);
        // }
        // 
        $this->verificarVendedor($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->estaDisponible() && $product->categories()->count() == 0) {
                return $this->errorResponse('Un producto activo debe tener al menos una categoría', 409);
            }
        }

        if ($product->isClean()) { // isClean: Verifica que la instancia no haya cambiado
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar.', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verificarVendedor($seller, $product);

        Storage::delete($product->image);
        // Storage::disk('images')->delete($product->image);

        $product->delete();

        return $this->showOne($product);
    }

    protected function verificarVendedor(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            // return $this->errorResponse('El vendedor especificado no es el vendedor real del producto.', 422);
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto.');
        }

    }
}

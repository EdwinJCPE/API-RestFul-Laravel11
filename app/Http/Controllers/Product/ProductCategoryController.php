<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        // Obtener la lista de categorias de un producto
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Product $product)
    // public function update(Request $request, string $product_id, string $category_id)
    public function update(Request $request, Product $product, Category $category)
    {
        // Agregar una categoría a un producto existente
        // $product = Product::findOrFail($product_id);
        // $category = Category::findOrFail($category_id);

        // dd($product, $category);
        // 
        // sync, attach, syncWithoutDetaching
        // 
        // $product->categories()->sync([$category->id]); // Sincronización Completa (Elimina y Añade)
        // $product->categories()->attach([$category->id]); // Agregar Registros (Permite Duplicados)
        // 
        $product->categories()->syncWithoutDetaching([$category->id]); // Añadir sin Eliminar (Evita Duplicados)

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->findOrFail($category->id)) {
            return $this->errorResponse('La categoría especificada no es una categoría de este producto.', 404);            
        }

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);
    }
}

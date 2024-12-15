<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all();
        // $usuarios = User::get();

        // return $usuarios;
        // return response()->json($usuarios, 200);
        return response()->json(['data' => $usuarios], 200);

        // $headers = [
        //     'Content-Type' => 'application/json; charset=utf-8',
        // ];
        // return response()->json($usuarios, 200, $headers);
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $usuario = User::find($id);
        $usuario = User::findOrFail($id);

        return response()->json(['data' => $usuario], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

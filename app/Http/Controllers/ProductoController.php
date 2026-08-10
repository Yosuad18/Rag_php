<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    // Guarda el nuevo producto en MongoDB
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'atributos' => 'nullable|json', // Validamos que el texto ingresado sea un JSON válido
        ]);

        // Decodificamos el JSON ingresado a un array de PHP
        $atributosArray = $request->filled('atributos') 
            ? json_decode($request->atributos, true) 
            : [];

        Producto::create([
            'nombre' => $request->nombre,
            'precio' => (int) $request->precio,
            'atributos' => $atributosArray, // El cast del modelo lo guardará como documento embebido
        ]);

        return redirect()->back()->with('success', '¡Producto guardado exitosamente en MongoDB!');
    }
}

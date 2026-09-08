<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::paginate(10);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'precio'    => 'required|numeric|min:0',
            'atributos' => 'nullable|array',
        ]);

        Producto::create($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'precio'    => 'required|numeric|min:0',
            'atributos' => 'nullable|array',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    public function consultarIA(Request $request)
    {
        $preguntaUsuario = $request->input('message', $request->input('pregunta', '¿Qué productos tienen guardados?'));

        $productos = Producto::all();

        if ($productos->isEmpty()) {
            return response()->json([
                'reply' => 'Actualmente no hay productos registrados en la base de datos.'
            ]);
        }

        $contextoCatalogo = "";
        foreach ($productos as $p) {
            $atributosTexto = is_array($p->atributos)
                ? json_encode($p->atributos, JSON_UNESCAPED_UNICODE)
                : $p->atributos;

            $contextoCatalogo .= "- Producto: {$p->nombre} | Precio: \${$p->precio} | Detalles: {$atributosTexto}\n";
        }

        $systemPrompt = "Eres un asistente de ventas virtual. Responde las dudas del cliente basándote ÚNICAMENTE en el siguiente catálogo de productos en tiempo real:\n\n" . $contextoCatalogo;

        $apiKey = env('OPENAI_API_KEY');
        $model = env('OPENAI_CHAT_MODEL', 'gpt-4o-mini');

        if (!$apiKey) {
            return response()->json([
                'reply' => 'Error: La variable OPENAI_API_KEY no está definida en el archivo .env'
            ]);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $preguntaUsuario],
            ],
            'temperature' => 0.3,
        ]);

        if (!$response->successful()) {
            $errorMessage = $response->json('error.message') ?? 'Error al comunicarse con la API de OpenAI.';
            return response()->json([
                'reply' => 'Error de OpenAI: ' . $errorMessage
            ], 200);
        }

        $botReply = $response->json('choices.0.message.content');
        return response()->json(['reply' => $botReply]);
    }
}

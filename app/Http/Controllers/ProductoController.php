<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    /**
     * Muestra el listado de productos.
     */
    public function index()
    {
        $productos = Producto::paginate(10);
        return view('productos.index', compact('productos'));
    }

    /**
     * Consulta RAG asistida por Inteligencia Artificial sobre el catálogo.
     */
    public function consultarIA(Request $request)
    {
        // Soporta tanto 'message' (desde JS) como 'pregunta' (desde Query String)
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

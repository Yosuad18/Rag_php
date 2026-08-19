<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Producto;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');

        // 1. Obtener los productos de MongoDB para dar contexto a la IA
        $productos = Producto::all(['nombre', 'precio', 'atributos']);

        $contexto = "Inventario actual de la tienda:\n";
        foreach ($productos as $p) {
            $detalles = json_encode($p->atributos, JSON_UNESCAPED_UNICODE);
            $contexto .= "- {$p->nombre} | Precio: ${$p->precio} | Detalles: {$detalles}\n";
        }

        // 2. Construir el Prompt (Instrucciones + Contexto + Pregunta)
        $prompt = "Eres un asistente de ventas virtual. Tu trabajo es responder preguntas de los clientes basándote ÚNICAMENTE en el siguiente inventario de productos. Si te preguntan algo que no está en el inventario, indica amablemente que no tienes esa información o que el producto no está disponible en este momento.\n\n"
                . $contexto . "\n\n"
                . "Pregunta del cliente: " . $userMessage;

        // 3. Consumir la API de Google Gemini (gemini-1.5-flash)
        $apiKey = env('GEMINI_API_KEY');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            // Extraer la respuesta del JSON de Gemini
            $botReply = $response->json('candidates.0.content.parts.0.text');
            return response()->json(['reply' => $botReply]);
        }

        return response()->json(['reply' => 'Error al conectar con la IA.'], 500);
    }
}

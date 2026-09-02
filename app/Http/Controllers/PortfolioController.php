<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use Illuminate\Support\Facades\Http;

class PortfolioController extends Controller
{
    public function consultarIA(Request $request)
    {
        $preguntaUsuario = $request->input('pregunta', '¿Cómo está distribuido mi portafolio y qué me recomiendas?');

        // 1. Obtener los registros guardados en MongoDB Atlas
        $items = PortfolioItem::all();

        if ($items->isEmpty()) {
            return response()->json([
                'respuesta' => 'Tu portafolio en MongoDB no tiene registros aún. Agrega algunas acciones primero.'
            ]);
        }

        // 2. Calcular el total invertido
        $totalInvertido = $items->sum(function ($item) {
            return $item->cantidad * $item->precio_compra;
        });

        // 3. Formatear los registros de MongoDB como texto para la IA
        $contextoAcciones = "";
        foreach ($items as $item) {
            $subtotal = $item->cantidad * $item->precio_compra;
            $contextoAcciones .= "- Ticker: {$item->ticker} | Cantidad: {$item->cantidad} | Precio Compra: \${$item->precio_compra} USD | Total: \${$subtotal} USD | Notas: {$item->notas}\n";
        }

        // 4. Construir el prompt con el contexto
        $prompt = <<<EOT
Eres un asesor de inversiones virtual. Tu tarea es responder a las preguntas del usuario basándote EXCLUSIVAMENTE en la información de su portafolio en MongoDB enviada a continuación.

--- DATOS DEL PORTAFOLIO EN MONGODB ---
Total Invertido Global: \${$totalInvertido} USD
Detalle de Posiciones:
{$contextoAcciones}
---------------------------------------

Pregunta del usuario: "{$preguntaUsuario}"

Responde de forma clara, profesional y usando viñetas.
EOT;

        // 5. Enviar la consulta a la API de Google Gemini
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
            $botReply = $response->json('candidates.0.content.parts.0.text');
            return response()->json([
                'pregunta'  => $preguntaUsuario,
                'respuesta' => $botReply
            ]);
        }

        return response()->json([
            'error' => 'No se pudo conectar con Gemini. Verifica que GEMINI_API_KEY esté en el archivo .env.'
        ], 500);
    }
}

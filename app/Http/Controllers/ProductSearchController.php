<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ProductSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $userQuery = trim($validated['query']);

        try {
            // 1. Generar el embedding de la pregunta del usuario con OpenAI
            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $userQuery,
            ]);

            $queryVector = $response->embeddings[0]->embedding;

            // 2. Consultar MongoDB Atlas con $vectorSearch
            $products = Producto::raw(function ($collection) use ($queryVector) {
                return $collection->aggregate([
                    [
                        '$vectorSearch' => [
                            'index' => 'vector_index',
                            'path' => 'embedding',
                            'queryVector' => $queryVector,
                            'numCandidates' => 100,
                            'limit' => 5,
                        ]
                    ],
                    [
                        '$project' => [
                            'name' => 1,
                            'description' => 1,
                            'price' => 1,
                            'stock' => 1,
                            'image' => 1, // <--- Incluido para renderizar en el frontend
                            'score' => ['$meta' => 'vectorSearchScore'],
                        ]
                    ]
                ]);
            });

            // 3. Crear el contexto para la IA
            $context = $products->map(fn($p) => "- {$p->name}: {$p->description} (\${$p->price}, Stock: {$p->stock})")->implode("\n");

            // 4. Generar la respuesta en lenguaje natural con GPT
            $completion = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres un asistente de e-commerce servicial y conciso. Responde a la pregunta del usuario basándote ÚNICAMENTE en este catálogo de productos recuperados:\n\n{$context}\n\nSi no encuentras productos que respondan a lo que busca, infórmalo amablemente."
                    ],
                    [
                        'role' => 'user',
                        'content' => $userQuery
                    ],
                ],
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'answer' => $completion->choices[0]->message->content,
                    'products' => $products,
                ],
            ]);

        } catch (\Throwable $e) {
            \Log::error("Error en RAG ProductSearch: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar la búsqueda vectorial.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

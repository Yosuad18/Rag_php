<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            // 1. Generar embedding con OpenAI
            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $userQuery,
            ]);

            $queryVector = $response->embeddings[0]->embedding;

            // 2. Búsqueda Vectorial en MongoDB Atlas
            $indexName = env('MONGODB_VECTOR_INDEX', 'vector_index');

            $products = Producto::raw(function ($collection) use ($queryVector, $indexName) {
                return $collection->aggregate([
                    [
                        '$vectorSearch' => [
                            'index' => $indexName,
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
                            'status' => 1,
                            'score' => ['$meta' => 'vectorSearchScore'],
                        ]
                    ]
                ]);
            });

            $productsCollection = collect($products);

            // 3. Formatear contexto con los nombres de campos correctos
            $context = $productsCollection->isEmpty()
                ? "No se encontraron productos en el catálogo."
                : $productsCollection->map(function ($p) {
                    return "- {$p->name} | Precio: \${$p->price} | Descripción: {$p->description}";
                })->implode("\n");

            // 4. Generar respuesta RAG
            $completion = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'temperature' => 0.3,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres un asistente de e-commerce útil. Responde basado en este catálogo:\n\n{$context}"
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
                    'products' => $productsCollection,
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error("Error RAG: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

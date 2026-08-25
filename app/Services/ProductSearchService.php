<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ProductSearchService
{
    private MongoDBVectorService $vectorService;
    private OpenAIService $openAIService;

    public function __construct(
        MongoDBVectorService $vectorService,
        OpenAIService $openAIService
    ) {
        $this->vectorService = $vectorService;
        $this->openAIService = $openAIService;
    }

    /**
     * Search for products using vector similarity and generate an AI response.
     *
     * Flow: User Query → OpenAI Embedding → MongoDB Vector Search → OpenAI Chat Response
     *
     * @param string $query The user's natural language query
     * @param int $limit Maximum number of products to retrieve
     * @return array Contains 'products' and 'answer' keys
     */
    public function search(string $query, int $limit = 5): array
    {
        try {
            // Step 1: Generate embedding for the user query
            Log::info('Generating embedding for query', ['query' => $query]);
            $queryVector = $this->openAIService->getEmbedding($query);

            if (empty($queryVector)) {
                throw new \RuntimeException('Failed to generate embedding for the query.');
            }

            // Step 2: Search MongoDB for similar products
            Log::info('Performing vector search', ['dimensions' => count($queryVector)]);
            $products = $this->vectorService->searchSimilarProducts($queryVector, $limit);

            // Step 3: Generate AI response based on found products
            Log::info('Generating AI response', ['products_found' => count($products)]);
            $answer = $this->openAIService->generateResponse($query, $products);

            return [
                'products' => $products,
                'answer' => $answer,
            ];
        } catch (\Exception $e) {
            Log::error('Product search failed: ' . $e->getMessage());
            throw $e;
        }
    }
}

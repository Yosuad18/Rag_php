<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private string $apiKey;
    private string $embeddingModel;
    private string $chatModel;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->embeddingModel = config('services.openai.embedding_model');
        $this->chatModel = config('services.openai.chat_model');
    }

    /**
     * Generate an embedding vector for the given text.
     *
     * @param string $text The text to embed
     * @return array The embedding vector (1536 dimensions for text-embedding-3-small)
     */
    public function getEmbedding(string $text): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/embeddings', [
                'input' => $text,
                'model' => $this->embeddingModel,
            ]);

            if ($response->failed()) {
                Log::error('OpenAI embedding request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \RuntimeException('Failed to generate embedding: ' . $response->body());
            }

            $data = $response->json();

            return $data['data'][0]['embedding'] ?? [];
        } catch (\Exception $e) {
            Log::error('OpenAI embedding error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a natural language response using the user query and relevant products.
     *
     * @param string $userQuery The original user question
     * @param array $products The products found via vector search
     * @return string AI-generated response
     */
    public function generateResponse(string $userQuery, array $products): string
    {
        try {
            $productsContext = $this->formatProductsForContext($products);

            $systemPrompt = <<<PROMPT
            You are a helpful product recommendation assistant. Your job is to answer
            user questions about products based on the catalog data provided.
            
            Rules:
            - Answer in the same language the user writes in (Spanish or English).
            - Be concise but helpful.
            - Reference specific products by name when relevant.
            - Include prices when mentioning products.
            - If no products match, say so honestly.
            - Never fabricate product information.
            PROMPT;

            $userMessage = <<<MESSAGE
            User question: "{$userQuery}"
            
            Relevant products from our catalog:
            {$productsContext}
            
            Please provide a helpful response based on these products.
            MESSAGE;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->chatModel,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->failed()) {
                Log::error('OpenAI chat request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \RuntimeException('Failed to generate response: ' . $response->body());
            }

            $data = $response->json();

            return $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';
        } catch (\Exception $e) {
            Log::error('OpenAI chat error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Format products array into a readable context string for the AI.
     */
    private function formatProductsForContext(array $products): string
    {
        if (empty($products)) {
            return 'No products found.';
        }

        $lines = [];
        foreach ($products as $index => $product) {
            $lines[] = sprintf(
                "%d. %s — $%s (Stock: %s). %s [Score: %.4f]",
                $index + 1,
                $product['name'],
                number_format($product['price'], 2),
                $product['stock'],
                $product['description'],
                $product['score']
            );
        }

        return implode("\n", $lines);
    }
}

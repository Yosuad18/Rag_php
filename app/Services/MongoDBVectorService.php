<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\Collection;
use Illuminate\Support\Facades\Log;

class MongoDBVectorService
{
    private Client $client;
    private Collection $collection;
    private string $indexName;

    public function __construct()
    {
        $uri = config('database.connections.mongodb.url');
        $database = config('database.connections.mongodb.database');
        $collectionName = config('database.connections.mongodb.collection');
        $this->indexName = config('database.connections.mongodb.index');

        $this->client = new Client($uri);
        $this->collection = $this->client->selectDatabase($database)->selectCollection($collectionName);
    }

    /**
     * Search for similar products using vector similarity.
     *
     * @param array $queryVector The embedding vector to search for
     * @param int $limit Maximum number of results to return
     * @return array Matching products with similarity scores
     */
    public function searchSimilarProducts(array $queryVector, int $limit = 5): array
    {
        try {
            $pipeline = [
                [
                    '$vectorSearch' => [
                        'index' => $this->indexName,
                        'path' => 'embedding',
                        'queryVector' => $queryVector,
                        'numCandidates' => $limit * 10, // Oversample for better recall
                        'limit' => $limit,
                    ],
                ],
                [
                    '$addFields' => [
                        'score' => ['$meta' => 'vectorSearchScore'],
                    ],
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'name' => 1,
                        'description' => 1,
                        'price' => 1,
                        'stock' => 1,
                        'image' => 1,
                        'status' => 1,
                        'score' => 1,
                    ],
                ],
            ];

            $results = $this->collection->aggregate($pipeline)->toArray();

            return array_map(function ($doc) {
                return [
                    'id' => (string) $doc['_id'],
                    'name' => $doc['name'] ?? '',
                    'description' => $doc['description'] ?? '',
                    'price' => $doc['price'] ?? 0,
                    'stock' => $doc['stock'] ?? 0,
                    'image' => $doc['image'] ?? null,
                    'status' => $doc['status'] ?? 'active',
                    'score' => $doc['score'] ?? 0,
                ];
            }, $results);
        } catch (\Exception $e) {
            Log::error('MongoDB vector search failed: ' . $e->getMessage());
            throw $e;
        }
    }
}

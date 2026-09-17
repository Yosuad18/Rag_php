<?php

namespace App\Services;

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

class DynamoDBService
{
    protected DynamoDbClient $client;
    protected string $table;

    public function __construct()
    {
        $this->client = new DynamoDbClient([
            'region'  => config('services.dynamodb.region'),
            'version' => 'latest',
            'credentials' => config('services.dynamodb.key')
                ? [
                    'key'    => config('services.dynamodb.key'),
                    'secret' => config('services.dynamodb.secret'),
                ]
                : null,
        ]);

        $this->table = config('services.dynamodb.table');
    }

    public function putItem(array $item): array
    {
        try {
            $result = $this->client->putItem([
                'TableName' => $this->table,
                'Item'      => $this->marshalItem($item),
            ]);

            return $result->toArray();
        } catch (AwsException $e) {
            Log::error('DynamoDB putItem failed', [
                'error' => $e->getMessage(),
                'item'  => $item,
            ]);
            throw $e;
        }
    }

    public function getItem(string $key, string $rangeKey = null): ?array
    {
        $keyCondition = ['id' => ['S' => $key]];

        if ($rangeKey !== null) {
            $keyCondition['created_at'] = ['S' => $rangeKey];
        }

        try {
            $result = $this->client->getItem([
                'TableName' => $this->table,
                'Key'       => $keyCondition,
            ]);

            $item = $result->getItem();

            return $item ? $this->unmarshalItem($item) : null;
        } catch (AwsException $e) {
            Log::error('DynamoDB getItem failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function query(array $params): array
    {
        try {
            $result = $this->client->query([
                'TableName'                 => $this->table,
                'KeyConditionExpression'    => $params['key_condition'],
                'ExpressionAttributeValues' => $this->marshalItem($params['values']),
                'IndexName'                 => $params['index'] ?? null,
                'ScanIndexForward'          => $params['ascending'] ?? false,
                'Limit'                     => $params['limit'] ?? null,
            ]);

            return [
                'items' => array_map([$this, 'unmarshalItem'], $result->getItems()),
                'count' => $result->getCount(),
            ];
        } catch (AwsException $e) {
            Log::error('DynamoDB query failed', ['error' => $e->getMessage(), 'params' => $params]);
            throw $e;
        }
    }

    public function scan(array $params = []): array
    {
        try {
            $args = [
                'TableName' => $this->table,
            ];

            if (isset($params['filter_expression'])) {
                $args['FilterExpression'] = $params['filter_expression'];
            }

            if (isset($params['expression_attribute_values'])) {
                $args['ExpressionAttributeValues'] = $this->marshalItem($params['expression_attribute_values']);
            }

            if (isset($params['limit'])) {
                $args['Limit'] = $params['limit'];
            }

            $result = $this->client->scan($args);

            return [
                'items' => array_map([$this, 'unmarshalItem'], $result->getItems()),
                'count' => $result->getCount(),
            ];
        } catch (AwsException $e) {
            Log::error('DynamoDB scan failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteItem(string $key, string $rangeKey = null): array
    {
        $keyCondition = ['id' => ['S' => $key]];

        if ($rangeKey !== null) {
            $keyCondition['created_at'] = ['S' => $rangeKey];
        }

        try {
            $result = $this->client->deleteItem([
                'TableName' => $this->table,
                'Key'       => $keyCondition,
            ]);

            return $result->toArray();
        } catch (AwsException $e) {
            Log::error('DynamoDB deleteItem failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function marshalItem(array $item): array
    {
        $marshaler = new \Aws\DynamoDb\Marshaler();
        return $marshaler->marshalJson(json_encode($item));
    }

    protected function unmarshalItem(array $item): array
    {
        $marshaler = new \Aws\DynamoDb\Marshaler();
        return json_decode($marshaler->marshalJson($item), true);
    }
}

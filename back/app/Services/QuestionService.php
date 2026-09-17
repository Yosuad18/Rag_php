<?php

namespace App\Services;

use Illuminate\Support\Str;

class QuestionService
{
    protected DynamoDBService $dynamoDB;

    public function __construct(DynamoDBService $dynamoDB)
    {
        $this->dynamoDB = $dynamoDB;
    }

    public function create(array $data): array
    {
        $question = [
            'id'             => (string) Str::ulid(),
            'candidate_id'   => $data['candidate_id'],
            'question_text'  => $data['question_text'],
            'source'         => $data['source'] ?? 'web',
            'topic'          => $data['topic'] ?? $this->categorize($data['question_text']),
            'status'         => 'pending',
            'created_at'     => now()->toIso8601String(),
            'metadata'       => $data['metadata'] ?? [],
        ];

        $this->dynamoDB->putItem($question);

        return $question;
    }

    public function getById(string $id, string $createdAt): ?array
    {
        return $this->dynamoDB->getItem($id, $createdAt);
    }

    public function getByCandidate(string $candidateId, int $limit = 25): array
    {
        return $this->dynamoDB->query([
            'key_condition' => 'candidate_id = :cid',
            'values'        => [':cid' => $candidateId],
            'index'         => 'candidate-id-index',
            'ascending'     => false,
            'limit'         => $limit,
        ]);
    }

    public function getByTopic(string $topic, int $limit = 50): array
    {
        return $this->dynamoDB->query([
            'key_condition' => 'topic = :topic',
            'values'        => [':topic' => $topic],
            'index'         => 'topic-created_at-index',
            'ascending'     => false,
            'limit'         => $limit,
        ]);
    }

    public function list(int $limit = 25, ?string $topic = null, ?string $status = null): array
    {
        $params = [];

        if ($topic || $status) {
            $conditions = [];
            $values = [];

            if ($topic) {
                $conditions[] = 'topic = :topic';
                $values[':topic'] = $topic;
            }

            if ($status) {
                $conditions[] = '#status = :status';
                $values[':status'] = $status;
            }

            $params['filter_expression'] = implode(' AND ', $conditions);
            $params['expression_attribute_values'] = $values;
        }

        $params['limit'] = $limit;

        return $this->dynamoDB->scan($params);
    }

    public function updateStatus(string $id, string $createdAt, string $status): array
    {
        $item = $this->dynamoDB->getItem($id, $createdAt);

        if (!$item) {
            throw new \RuntimeException('Question not found');
        }

        $item['status'] = $status;
        $this->dynamoDB->putItem($item);

        return $item;
    }

    public function delete(string $id, string $createdAt): array
    {
        return $this->dynamoDB->deleteItem($id, $createdAt);
    }

    public function getTopicStats(): array
    {
        $topics = ['salary', 'process', 'technical', 'benefits', 'culture', 'role'];
        $stats = [];

        foreach ($topics as $topic) {
            $result = $this->getByTopic($topic, 1000);
            $stats[$topic] = [
                'count' => $result['count'],
                'topic' => $topic,
            ];
        }

        return $stats;
    }

    public function getTimelineStats(string $period = 'week'): array
    {
        $result = $this->dynamoDB->scan(['limit' => 1000]);
        $grouped = [];

        foreach ($result['items'] as $item) {
            $date = \Carbon\Carbon::parse($item['created_at']);

            $key = match ($period) {
                'day'   => $date->format('Y-m-d'),
                'week'  => $date->format('Y-W'),
                'month' => $date->format('Y-m'),
                default => $date->format('Y-m-d'),
            };

            $grouped[$key] = ($grouped[$key] ?? 0) + 1;
        }

        ksort($grouped);

        return array_map(fn ($count, $label) => ['period' => $label, 'count' => $count], $grouped, array_keys($grouped));
    }

    protected function categorize(string $text): string
    {
        $text = strtolower($text);

        $categories = [
            'salary'    => ['salary', 'pay', 'compensation', 'wage', 'earnings', 'income'],
            'benefits'  => ['insurance', 'health', '401k', 'vacation', 'pto', 'perks', 'dental', 'vision'],
            'process'   => ['interview', 'process', 'timeline', 'next steps', 'when', 'hiring', 'decision'],
            'technical' => ['skills', 'requirements', 'technology', 'stack', 'tools', 'experience', 'technical'],
            'culture'   => ['culture', 'team', 'environment', 'remote', 'office', 'work life', 'balance'],
            'role'      => ['responsibilities', 'duties', 'day-to-day', 'expectations', 'role', 'position'],
        ];

        foreach ($categories as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $topic;
                }
            }
        }

        return 'general';
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function __construct(
        protected QuestionService $questionService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = $request->get('limit', 25);
        $topic = $request->get('topic');
        $status = $request->get('status');

        $result = $this->questionService->list($limit, $topic, $status);

        return QuestionResource::collection($result['items']);
    }

    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $question = $this->questionService->create($request->validated());

        return response()->json([
            'data'    => new QuestionResource($question),
            'message' => 'Question stored successfully.',
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $createdAt = $request->query('created_at');

        if (!$createdAt) {
            return response()->json(['message' => 'created_at query parameter is required.'], 400);
        }

        $question = $this->questionService->getById($id, $createdAt);

        if (!$question) {
            return response()->json(['message' => 'Question not found.'], 404);
        }

        return response()->json([
            'data' => new QuestionResource($question),
        ]);
    }

    public function byCandidate(string $candidateId): AnonymousResourceCollection
    {
        $result = $this->questionService->getByCandidate($candidateId);

        return QuestionResource::collection($result['items']);
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status'     => 'required|string|in:pending,answered,archived',
            'created_at' => 'required|string',
        ]);

        $question = $this->questionService->updateStatus(
            $id,
            $request->created_at,
            $request->status
        );

        return response()->json([
            'data'    => new QuestionResource($question),
            'message' => 'Question status updated.',
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $createdAt = $request->query('created_at');

        if (!$createdAt) {
            return response()->json(['message' => 'created_at query parameter is required.'], 400);
        }

        $this->questionService->delete($id, $createdAt);

        return response()->json([
            'message' => 'Question deleted successfully.',
        ], 204);
    }

    public function analyticsTopics(): JsonResponse
    {
        $stats = $this->questionService->getTopicStats();

        return response()->json([
            'data' => $stats,
        ]);
    }

    public function analyticsTimeline(Request $request): JsonResponse
    {
        $period = $request->get('period', 'week');
        $stats = $this->questionService->getTimelineStats($period);

        return response()->json([
            'data' => $stats,
        ]);
    }
}

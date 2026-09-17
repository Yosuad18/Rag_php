<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInterviewRequest;
use App\Http\Requests\UpdateInterviewRequest;
use App\Http\Resources\InterviewResource;
use App\Models\Interview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InterviewController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Interview::with(['candidate', 'interviewer']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $interviews = $query->orderBy('scheduled_at', 'desc')->paginate(15);

        return InterviewResource::collection($interviews);
    }

    public function store(StoreInterviewRequest $request): JsonResponse
    {
        $interview = Interview::create($request->validated());
        $interview->load(['candidate', 'interviewer']);

        return response()->json([
            'data' => new InterviewResource($interview),
            'message' => 'Interview scheduled successfully.',
        ], 201);
    }

    public function show(Interview $interview): JsonResponse
    {
        $interview->load(['candidate', 'interviewer']);

        return response()->json([
            'data' => new InterviewResource($interview),
        ]);
    }

    public function update(UpdateInterviewRequest $request, Interview $interview): JsonResponse
    {
        $interview->update($request->validated());
        $interview->load(['candidate', 'interviewer']);

        return response()->json([
            'data' => new InterviewResource($interview),
            'message' => 'Interview updated successfully.',
        ]);
    }

    public function destroy(Interview $interview): JsonResponse
    {
        $interview->delete();

        return response()->json([
            'message' => 'Interview deleted successfully.',
        ], 204);
    }
}

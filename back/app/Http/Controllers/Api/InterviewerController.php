<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInterviewerRequest;
use App\Http\Requests\UpdateInterviewerRequest;
use App\Http\Resources\InterviewerResource;
use App\Models\Interviewer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InterviewerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Interviewer::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $interviewers = $query->orderBy('created_at', 'desc')->paginate(15);

        return InterviewerResource::collection($interviewers);
    }

    public function store(StoreInterviewerRequest $request): JsonResponse
    {
        $interviewer = Interviewer::create($request->validated());

        return response()->json([
            'data' => new InterviewerResource($interviewer),
            'message' => 'Interviewer created successfully.',
        ], 201);
    }

    public function show(Interviewer $interviewer): JsonResponse
    {
        $interviewer->load('interviews');

        return response()->json([
            'data' => new InterviewerResource($interviewer),
        ]);
    }

    public function update(UpdateInterviewerRequest $request, Interviewer $interviewer): JsonResponse
    {
        $interviewer->update($request->validated());

        return response()->json([
            'data' => new InterviewerResource($interviewer),
            'message' => 'Interviewer updated successfully.',
        ]);
    }

    public function destroy(Interviewer $interviewer): JsonResponse
    {
        $interviewer->delete();

        return response()->json([
            'message' => 'Interviewer deleted successfully.',
        ], 204);
    }
}

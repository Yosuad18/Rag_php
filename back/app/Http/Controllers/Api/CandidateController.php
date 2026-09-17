<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CandidateController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Candidate::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position_applied', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate(15);

        return CandidateResource::collection($candidates);
    }

    public function store(StoreCandidateRequest $request): JsonResponse
    {
        $candidate = Candidate::create($request->validated());

        return response()->json([
            'data' => new CandidateResource($candidate),
            'message' => 'Candidate created successfully.',
        ], 201);
    }

    public function show(Candidate $candidate): JsonResponse
    {
        $candidate->load('interviews');

        return response()->json([
            'data' => new CandidateResource($candidate),
        ]);
    }

    public function update(UpdateCandidateRequest $request, Candidate $candidate): JsonResponse
    {
        $candidate->update($request->validated());

        return response()->json([
            'data' => new CandidateResource($candidate),
            'message' => 'Candidate updated successfully.',
        ]);
    }

    public function destroy(Candidate $candidate): JsonResponse
    {
        $candidate->delete();

        return response()->json([
            'message' => 'Candidate deleted successfully.',
        ], 204);
    }
}

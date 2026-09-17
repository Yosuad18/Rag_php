<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Interviewer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalCandidates = Candidate::count();
        $pendingInterviews = Interview::where('status', 'scheduled')->count();
        $completedInterviews = Interview::where('status', 'completed')->count();
        $totalInterviewers = Interviewer::count();
        $hiredCandidates = Candidate::where('status', 'hired')->count();
        $rejectedCandidates = Candidate::where('status', 'rejected')->count();

        $recentInterviews = Interview::with(['candidate', 'interviewer'])
            ->orderBy('scheduled_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingInterviews = Interview::with(['candidate', 'interviewer'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get();

        return response()->json([
            'data' => [
                'total_candidates' => $totalCandidates,
                'pending_interviews' => $pendingInterviews,
                'completed_interviews' => $completedInterviews,
                'total_interviewers' => $totalInterviewers,
                'hired_candidates' => $hiredCandidates,
                'rejected_candidates' => $rejectedCandidates,
                'recent_interviews' => $recentInterviews,
                'upcoming_interviews' => $upcomingInterviews,
            ],
        ]);
    }
}

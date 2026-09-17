<?php

use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\InterviewerController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Candidates
    Route::get('/candidates', [CandidateController::class, 'index']);
    Route::post('/candidates', [CandidateController::class, 'store']);
    Route::get('/candidates/{candidate}', [CandidateController::class, 'show']);
    Route::put('/candidates/{candidate}', [CandidateController::class, 'update']);
    Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy']);

    // Interviews
    Route::get('/interviews', [InterviewController::class, 'index']);
    Route::post('/interviews', [InterviewController::class, 'store']);
    Route::get('/interviews/{interview}', [InterviewController::class, 'show']);
    Route::put('/interviews/{interview}', [InterviewController::class, 'update']);
    Route::delete('/interviews/{interview}', [InterviewController::class, 'destroy']);

    // Interviewers
    Route::get('/interviewers', [InterviewerController::class, 'index']);
    Route::post('/interviewers', [InterviewerController::class, 'store']);
    Route::get('/interviewers/{interviewer}', [InterviewerController::class, 'show']);
    Route::put('/interviewers/{interviewer}', [InterviewerController::class, 'update']);
    Route::delete('/interviewers/{interviewer}', [InterviewerController::class, 'destroy']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Questions (DynamoDB)
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::post('/questions', [QuestionController::class, 'store']);
    Route::get('/questions/analytics/topics', [QuestionController::class, 'analyticsTopics']);
    Route::get('/questions/analytics/timeline', [QuestionController::class, 'analyticsTimeline']);
    Route::get('/questions/{id}', [QuestionController::class, 'show']);
    Route::put('/questions/{id}/status', [QuestionController::class, 'updateStatus']);
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

    // Candidate-specific questions
    Route::get('/candidates/{candidateId}/questions', [QuestionController::class, 'byCandidate']);
});

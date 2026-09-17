<?php

use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\InterviewerController;
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
});

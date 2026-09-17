<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'interviewer_id' => $this->interviewer_id,
            'candidate' => new CandidateResource($this->whenLoaded('candidate')),
            'interviewer' => new InterviewerResource($this->whenLoaded('interviewer')),
            'scheduled_at' => $this->scheduled_at,
            'duration_minutes' => $this->duration_minutes,
            'type' => $this->type,
            'status' => $this->status,
            'location' => $this->location,
            'feedback' => $this->feedback,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

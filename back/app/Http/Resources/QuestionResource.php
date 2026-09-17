<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->resource['id'] ?? null,
            'candidate_id'  => $this->resource['candidate_id'] ?? null,
            'question_text' => $this->resource['question_text'] ?? null,
            'source'        => $this->resource['source'] ?? null,
            'topic'         => $this->resource['topic'] ?? null,
            'status'        => $this->resource['status'] ?? null,
            'created_at'    => $this->resource['created_at'] ?? null,
            'metadata'      => $this->resource['metadata'] ?? null,
        ];
    }
}

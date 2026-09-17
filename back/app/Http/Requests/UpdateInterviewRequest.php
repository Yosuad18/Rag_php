<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'candidate_id' => 'sometimes|required|string',
            'interviewer_id' => 'sometimes|required|string',
            'scheduled_at' => 'sometimes|required|date',
            'duration_minutes' => 'sometimes|required|integer|min:15|max:480',
            'type' => 'sometimes|required|string|in:technical,behavioral,phone,screening',
            'status' => 'sometimes|required|string|in:scheduled,completed,cancelled,no_show',
            'location' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }
}

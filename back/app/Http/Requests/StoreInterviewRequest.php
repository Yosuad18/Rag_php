<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'candidate_id' => 'required|string',
            'interviewer_id' => 'required|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'type' => 'required|string|in:technical,behavioral,phone,screening',
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show',
            'location' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }
}

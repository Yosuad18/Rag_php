<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'candidate_id'  => 'required|string|max:255',
            'question_text' => 'required|string|max:5000',
            'source'        => 'nullable|string|in:web,api,email',
            'topic'         => 'nullable|string|in:salary,benefits,process,technical,culture,role,general',
            'metadata'      => 'nullable|array',
        ];
    }
}

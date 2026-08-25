<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:cash,card,transfer'],
            'status' => ['required', 'string', 'in:pending,completed,failed,refunded'],
            'description' => ['nullable', 'string'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}

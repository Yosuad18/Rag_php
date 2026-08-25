<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRepartidorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('repartidores')->ignore($this->route('repartidor'))],
            'phone' => ['nullable', 'string', 'max:20'],
            'vehicle_type' => ['required', 'string', 'in:moto,coche,bici,pie'],
            'license_plate' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string', 'in:available,busy,inactive'],
        ];
    }
}

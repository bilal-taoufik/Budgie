<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class PrevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_prevision' => ['required', 'date_format:Y-m-d', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_prevision.required' => 'La date de prévision est obligatoire.',
            'date_prevision.date_format' => 'La date de prévision est invalide.',
            'date_prevision.after' => 'La date de prévision doit être supérieur à aujourd hui.',
        ];
    }
}

<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
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
            'date_prevision' => ['required', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_prevision.required' => 'Le nom du compte est requis.',
            'date_prevision.after' => 'La date de prévision doit être après la date d\'aujoud\'hui.',
        ];
    }
}

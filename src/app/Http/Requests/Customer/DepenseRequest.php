<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DepenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'              => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'account_id'       => ['required', 'exists:accounts,id'],
            'montant'          => ['required', 'numeric', 'min:0'],
            'fractionnement'   => ['required', 'string', 'max:255'],
            'date_effet'       => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'            => 'Le nom de la dépense est requis.',
            'nom.string'              => 'Le nom doit être une chaîne de caractères.',
            'nom.max'                 => 'Le nom ne doit pas dépasser 255 caractères.',
            'account_id.required'     => 'Le compte associé est requis.',
            'account_id.exists'       => 'Le compte sélectionné est invalide.',
            'montant.required'        => 'Le montant est requis.',
            'montant.numeric'         => 'Le montant doit être un nombre.',
            'montant.min'             => 'Le montant ne peut pas être négatif.',
            'fractionnement.required' => 'Le fractionnement est requis.',
            'date_effet.required'     => 'La date d\'effet est requise.',
            'date_effet.date'         => 'La date d\'effet doit être une date valide.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'nom'            => ucwords(trim($this->nom)),
            'description'    => ucfirst(trim($this->description)),
            'montant'        => str_replace(',', '.', $this->montant),
        ]);
    }
}
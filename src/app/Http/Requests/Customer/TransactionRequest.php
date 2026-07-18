<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', Rule::exists('accounts', 'id')->where('user_id', auth()->id())],
            'type' => ['required', Rule::in(['revenu', 'depense'])],
            'nom' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0'],
            'fractionnement' => ['required', Rule::in(['unique', 'mensuel', 'semestriel', 'annuel'])],
            'date_effet' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_effet'],
            'derniere_application' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Veuillez selectionner un compte.',
            'account_id.exists' => 'Le compte selectionné est invalide.',
            'type.required' => 'Veuillez selectionner un type.',
            'type.in' => 'Le type selectionné est invalide.',
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne peut pas dépasser 40 caractères.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être minimum 0.',
            'fractionnement.required' => 'Veuillez selectionner une fréquence.',
            'fractionnement.in' => 'La frequence selectionnée est invalide.',
            'date_effet.required' => 'Veuillez selectionner une date.',
            'date_effet.date' => 'La date est invalide.',
            'date_fin.after_or_equal' => 'La date ne peux pas être avant la date d\'effet.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'nom' => ucwords(trim($this->nom)),
            'description' =>ucfirst(trim($this->description)),
        ]);
    }
}
<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RevenuRequest extends FormRequest
{
    public const FRACTIONNEMENTS = [
        'mensuel',
        'semestriel',
        'annuel',
        'une_fois',
    ];

    public function authorize(): bool
    {
        return true;
    }

    // Règles de validation pour la création et la mise à jour d'un revenu
    public function rules(): array
    {
        return [
            'account_id'     => ['required', 'exists:accounts,id'],
            'revenue_nom'            => ['required', 'string', 'max:255'],
            'revenue_description'    => ['nullable', 'string'],
            'revenue_montant'        => ['required', 'numeric', 'min:0'],
            'revenue_fractionnement' => ['required', Rule::in(self::FRACTIONNEMENTS)],
            'revenue_date_effet'     => ['required', 'date'],
        ];
    }

    // Messages d'erreur personnalisés
    public function messages(): array
    {
        return [
            'account_id.required'     => 'Le compte associé est requis',
            'account_id.exists'       => 'Le compte sélectionné est invalide',
            'revenue_nom.required'            => 'Le nom du revenu est requis',
            'revenue_nom.string'              => 'Le nom doit être une chaîne de caractères',
            'revenue_nom.max'                 => 'Le nom ne doit pas dépasser 255 caractères',
            'revenue_montant.required'        => 'Le montant est requis',
            'revenue_montant.numeric'         => 'Le montant doit être un nombre',
            'revenue_montant.min'             => 'Le montant ne peut pas être négatif',
            'revenue_fractionnement.required' => 'Le fractionnement est requis',
            'revenue_fractionnement.in'       => 'Le fractionnement selectionne est invalide',
            'revenue_date_effet.required'     => 'La date d\'effet est requise',
            'revenue_date_effet.date'         => 'La date d\'effet doit être une date valide',
        ];
    }

    // Nettoie et transforme les données avant la validation
    public function prepareForValidation(): void
    {
        $this->merge([
            // Capitalise chaque mot et supprime les espaces
            'revenue_nom'         => ucwords(trim($this->revenue_nom)),
            // Capitalise le premier caractère et supprime les espaces
            'revenue_description' => ucfirst(trim($this->revenue_description)),
            // Remplace la virgule par un point pour le format SQL
            'revenue_montant'     => str_replace(',', '.', $this->revenue_montant),
        ]);
    }
}
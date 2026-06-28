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
        'unique',
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
            'revenu_nom'            => ['required', 'string', 'max:255'],
            'revenu_description'    => ['nullable', 'string'],
            'revenu_montant'        => ['required', 'numeric', 'min:0'],
            'revenu_fractionnement' => ['required', Rule::in(self::FRACTIONNEMENTS)],
            'revenu_date_effet'     => ['required', 'date'],
        ];
    }

    // Messages d'erreur personnalisés
    public function messages(): array
    {
        return [
            'account_id.required'     => 'Le compte associé est requis',
            'account_id.exists'       => 'Le compte sélectionné est invalide',
            'revenu_nom.required'            => 'Le nom du revenu est requis',
            'revenu_nom.string'              => 'Le nom doit être une chaîne de caractères',
            'revenu_nom.max'                 => 'Le nom ne doit pas dépasser 255 caractères',
            'revenu_montant.required'        => 'Le montant est requis',
            'revenu_montant.numeric'         => 'Le montant doit être un nombre',
            'revenu_montant.min'             => 'Le montant ne peut pas être négatif',
            'revenu_fractionnement.required' => 'Le fractionnement est requis',
            'revenu_fractionnement.in'       => 'Le fractionnement selectionne est invalide',
            'revenu_date_effet.required'     => 'La date d\'effet est requise',
            'revenu_date_effet.date'         => 'La date d\'effet doit être une date valide',
        ];
    }

    // Nettoie et transforme les données avant la validation
    public function prepareForValidation(): void
    {
        $this->merge([
            // Capitalise chaque mot et supprime les espaces
            'revenu_nom'         => ucwords(trim($this->revenu_nom)),
            // Capitalise le premier caractère et supprime les espaces
            'revenu_description' => ucfirst(trim($this->revenu_description)),
            // Remplace la virgule par un point pour le format SQL
            'revenu_montant'     => str_replace(',', '.', $this->revenu_montant),
        ]);
    }
}
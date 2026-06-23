<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    
    // Règles de validation pour le formulaire de création et de mise à jour d'un compte
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'solde' => ['required', 'numeric', 'min:0'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    // Message d'erreur personnalisés pour les règles de validation
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du compte est requis.',
            'name.string' => 'Le nom du compte doit être une chaîne de caractères.',
            'name.max' => 'Le nom du compte ne doit pas dépasser 255 caractères.',
            'solde.required' => 'Le solde du compte est requis.',
            'solde.numeric' => 'Le solde du compte doit être un nombre.',
            'solde.min' => 'Le solde du compte ne peut pas être négatif.',
            'description.string' => 'La description du compte doit être une chaîne de caractères.',
            'interest_rate.required' => 'Le taux d\'intérêt est requis.',
            'interest_rate.numeric' => 'Le taux d\'intérêt doit être un nombre.',
            'interest_rate.min' => 'Le taux d\'intérêt ne peut pas être inférieur à 0%.',
            'interest_rate.max' => 'Le taux d\'intérêt ne peut pas dépasser 100%.',
            'tax_rate.required' => 'Le taux d\'imposition est requis.',
            'tax_rate.numeric' => 'Le taux d\'imposition doit être un nombre.',
            'tax_rate.min' => 'Le taux d\'imposition ne peut pas être inférieur à 0%.',
            'tax_rate.max' => 'Le taux d\'imposition ne peut pas dépasser 100%.',
        ];
    }

    // Préparer les données avant la validation (par exemple, convertir les valeurs en nombres à virgule flottante et supprimer les espaces)
    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords(trim($this->name)),
            'description' => ucfirst(trim($this->description)),
            'solde' => str_replace(',', '.', $this->solde),
            'interest_rate' => str_replace(',', '.', $this->interest_rate),
            'tax_rate' => str_replace(',', '.', $this->tax_rate),
        ]);
    }
}

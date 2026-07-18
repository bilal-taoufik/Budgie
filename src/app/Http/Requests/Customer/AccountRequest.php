<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'solde' => ['required', 'numeric', 'min:0'],
            'taux_remuneration' => ['required', 'numeric', 'min:0', 'max:100'],
            'taux_imposition' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du compte est obligatoire.',
            'nom.string' => 'Le nom du compte doit être une chaine de caractères.',
            'nom.max' => 'Le nom du compte ne doit pas dépasser 255 caractères.',
            'solde.required' => 'Le solde du compte est obligatoire.',
            'solde.numeric' => 'Le solde du compte doit être un nombre.',
            'solde.min' => 'Le solde du compte ne peut pas être négatif.',
            'description.string' => 'La description du compte doit être une chaine de caractères.',
            'taux_remuneration.required' => 'Le taux d\'intérêt est obligatoire.',
            'taux_remuneration.numeric' => 'Le taux d\'intérêt doit être un nombre.',
            'taux_remuneration.min' => 'Le taux d\'intérêt ne peut pas être infèrieur a 0%.',
            'taux_remuneration.max' => 'Le taux d\'intérêt ne peut pas dépasser 100%.',
            'taux_imposition.required' => 'Le taux d\'imposition est obligatoire.',
            'taux_imposition.numeric' => 'Le taux d\'imposition doit être un nombre.',
            'taux_imposition.min' => 'Le taux d\'imposition ne peut pas être infèrieur a 0%.',
            'taux_imposition.max' => 'Le taux d\'imposition ne peut pas dépasser 100%.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'nom' =>ucwords(trim($this->nom)),
            'description' => ucfirst($this->description),
            'solde' => trim($this->solde),
        ]);
    }
}

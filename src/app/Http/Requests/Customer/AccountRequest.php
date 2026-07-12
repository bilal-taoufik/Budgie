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
            'nom.required' => 'Le nom du compte est requis.',
            'nom.string' => 'Le nom du compte doit etre une chaine de caracteres.',
            'nom.max' => 'Le nom du compte ne doit pas depasser 255 caracteres.',
            'solde.required' => 'Le solde du compte est requis.',
            'solde.numeric' => 'Le solde du compte doit etre un nombre.',
            'solde.min' => 'Le solde du compte ne peut pas etre negatif.',
            'description.string' => 'La description du compte doit etre une chaine de caracteres.',
            'taux_remuneration.required' => 'Le taux d interet est requis.',
            'taux_remuneration.numeric' => 'Le taux d interet doit etre un nombre.',
            'taux_remuneration.min' => 'Le taux d interet ne peut pas etre inferieur a 0%.',
            'taux_remuneration.max' => 'Le taux d interet ne peut pas depasser 100%.',
            'taux_imposition.required' => 'Le taux d imposition est requis.',
            'taux_imposition.numeric' => 'Le taux d imposition doit etre un nombre.',
            'taux_imposition.min' => 'Le taux d imposition ne peut pas etre inferieur a 0%.',
            'taux_imposition.max' => 'Le taux d imposition ne peut pas depasser 100%.',
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

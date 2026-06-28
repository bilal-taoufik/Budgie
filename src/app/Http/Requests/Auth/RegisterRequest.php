<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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

    // Règles de validation pour le formulaire d'inscription
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
            'password_confirmation' => ['required', 'string', 'same:password'],
        ];
    }

    // Message d'erreur personnalisés pour les règles de validation
    public function messages(): array
    {
        return [
            'firstname.required' => 'Le prénom est requis.',
            'lastname.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.regex' => 'Le mot de passe doit contenir 12 caractères avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
            'password_confirmation.same' => 'Les mots de passe ne correspondent pas.',
            'password_confirmation.required' => 'La confirmation du mot de passe est requise.',
        ];
    }

    // Préparer les données avant la validation (par exemple, convertir l'e-mail en minuscules et supprimer les espaces)
    public function prepareForValidation(): void
    {       
        $this->merge([
            'firstname' => ucwords(strtolower(trim($this->firstname))),
            'lastname' => strtoupper(trim($this->lastname)),
            'email' => strtolower(trim($this->email)),
        ]);
    }
}

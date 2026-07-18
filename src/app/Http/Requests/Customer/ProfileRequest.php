<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
    public function rules(): array
    {
        if ($this->routeIs('customer.profile.info', 'admin.profile.info')) {
            return [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            ];
        }

        if ($this->routeIs('customer.profile.password', 'admin.profile.password')) {
            return [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'confirmed', 'min:12', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
            ];
        }

        return [
            'password' => ['required', 'current_password'],
        ];
    }

    // Messages d'erreur personnalises
    public function messages(): array
    {
        return [
            'firstname.required' => 'Le prenom est obligatoire.',
            'firstname.string' => 'Le prenom doit être une chaine de caractères.',
            'firstname.max' => 'Le prenom ne doit pas depasser 255 caractères.',

            'lastname.required' => 'Le nom est obligatoire.',
            'lastname.string' => 'Le nom doit être une chaine de caractères.',
            'lastname.max' => 'Le nom ne doit pas depasser 255 caractères.',

            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',

            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.current_password' => 'Le mot de passe est incorrect.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 12 caractères.',
            'password.regex' => 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère special.',
        ];
    }

    // Prepare les donnees avant la validation
    public function prepareForValidation(): void
    {
        $this->merge([
            'firstname' => ucwords(strtolower(trim($this->firstname))),
            'lastname' => strtoupper(strtolower(trim($this->lastname))),
            'email' => strtolower(trim($this->email)),
        ]);
    }
}


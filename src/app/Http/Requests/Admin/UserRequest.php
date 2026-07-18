<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', 'min:12', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
        ];
    }

    // Messages d'erreur personnalises
    public function messages(): array
    {
        return [
            'firstname.required' => 'Le prénom est requis.',
            'firstname.string' => 'Le prénom doit etre une chaine de caractères.',
            'firstname.max' => 'Le prénom ne doit pas depasser 255 caractères.',

            'lastname.required' => 'Le nom est requis.',
            'lastname.string' => 'Le nom doit etre une chaine de caractères.',
            'lastname.max' => 'Le nom ne doit pas depasser 255 caractères.',

            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit etre valide.',
            'email.unique' => 'Cette adresse e-mail est deja utilisée.',

            'password.required' => 'Le mot de passe est requis.',
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

<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
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

    // Règles de validation pour le formulaire de connexion
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:12', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
        ];
    }

    // Message d'erreur personnalisés pour les règles de validation
    public function messages(): array
    {
        return [
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir 12 caractères avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
            'password.regex' => 'Le mot de passe doit contenir 12 caractères avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
        ];
    }

    // Préparer les données avant la validation (par exemple, convertir l'e-mail en minuscules et supprimer les espaces)
    public function prepareForValidation(): void
    {       
        $this->merge([
            'email' => strtolower(trim($this->email)),
        ]);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        // Récupérer l'utilisateur par e-mail
        $user = User::where('email', $this->input('email'))->first();

        // Vérification si l'utilisateur existe
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'Aucun compte n\'est associé à cette adresse e-mail.',
            ]);
        }

        // Vérification si l'utilisateur a activé son compte, utilise flash pour garder l'état de l'e-mail non vérifié et afficher un message approprié
        if (! $user->email_verified) {
            $this->session()->flash('btn_resend', true);
            $this->session()->flash('email', $user->email);
    
            throw ValidationException::withMessages([
                'email' => 'Votre compte n\'a pas été activé.',
            ]);
        }
        // Vérification si le mot de passe est correct
        if (! Hash::check($this->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Le mot de passe est incorrect.',
            ]);
        }
        Auth::login($user);
    }
}

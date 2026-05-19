<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function ConnexionClient()
    {
        return view('ConnexionClient');
    }

    public function ConnecterClient(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
            'mdp' => ['required', 'string', 'min:6', 'max:255'],
        ], [
            'email.required' => 'L email est obligatoire.',
            'email.email' => 'Veuillez saisir un email valide.',
            'mdp.required' => 'Le mot de passe est obligatoire.',
            'mdp.min' => 'Le mot de passe doit contenir au moins 6 caracteres.',
        ]);

        $client = User::getConnexionP($validated['email'], $validated['mdp']);

        if ($client !== false) {
            $request->session()->put('client', [
                'nom' => $client['nom'],
                'prenom' => $client['prenom'],
                'email' => $validated['email'],
            ]);

            return redirect()
                ->route('client.accueil')
                ->with('success', 'Connexion reussie.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Identifiant ou mot de passe incorrect.']);
    }

    public function AfficherAccueil()
    {
        return view('ConnecterClient');
    }

    public function Inscription(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'prenom' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'age' => ['required', 'integer', 'min:13', 'max:120'],
            'tel' => ['required', 'string', 'regex:/^\+?[0-9 .()-]{8,20}$/'],
            'mdp' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom doit contenir uniquement des lettres, espaces, apostrophes ou tirets.',
            'prenom.required' => 'Le prenom est obligatoire.',
            'prenom.regex' => 'Le prenom doit contenir uniquement des lettres, espaces, apostrophes ou tirets.',
            'email.required' => 'L email est obligatoire.',
            'email.email' => 'Veuillez saisir un email valide.',
            'age.required' => 'L age est obligatoire.',
            'age.integer' => 'L age doit etre un nombre.',
            'age.max' => 'L age ne peut pas depasser 120 ans.',
            'age.min' => 'Vous devez avoir au moins 13 ans.',
            'tel.required' => 'Le telephone est obligatoire.',
            'tel.regex' => 'Le telephone doit contenir uniquement des chiffres et peut commencer par +.',
            'mdp.required' => 'Le mot de passe est obligatoire.',
            'mdp.min' => 'Le mot de passe doit contenir au moins 6 caracteres.',
            'mdp.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        try {
            User::enregistrerClient(
                $validated['nom'],
                $validated['prenom'],
                $validated['age'],
                $validated['email'],
                $validated['tel'],
                $validated['mdp']
            );

            return redirect()
                ->route('client.connexion')
                ->with('success', 'Inscription reussie, vous pouvez vous connecter.');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('mdp', 'mdp_confirmation'))
                ->withErrors(['email' => 'Impossible de creer le compte. Verifiez vos informations.']);
        }
    }

    public function Formulaire()
    {
        return view('Inscription');
    }

    public function ConsulterProfil()
    {
        return view('ConsulterProfil');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\VerifyMail;

class RegisterUserController extends Controller
{
    // Afficher le formulaire d'inscription
    public function create()
    {
        return view('auth.register');
    }

    // Enregistrer un nouvel utilisateur après la validation du formulaire d'inscription
    public function store(RegisterRequest $request)
    {

        $user = User::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'email_verification_token' => bin2hex(random_bytes(32)),
            'email_verification_expires_at' => now()->addHours(24),
        ]);

        // Debugging: Afficher le token de vérification de l'e-mail de l'utilisateur
        // dd($user->email_verification_token);

        if (now()->greaterThan($user->email_verification_expires_at)) {
            return redirect()->route('register')->with('error', 'Le lien de vérification a expiré. Veuillez vous inscrire à nouveau.');
        }

        Mail::to($user->email)->send(new WelcomeMail($user));
        $verificationUrl = route('verify.email', ['token' => $user->email_verification_token, 'email' => $user->email]);
        Mail::to($user->email)->send(new VerifyMail($user, $verificationUrl));

        return redirect()->route('login')->with('success', 'Inscription réussie ! Vérifiez votre adresse e-mail pour activer votre compte.');
    }
}

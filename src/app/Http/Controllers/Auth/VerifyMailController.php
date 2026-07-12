<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\VerifyMailRequest;
use Illuminate\Support\Facades\Mail;

class VerifyMailController extends Controller
{
    // Met à jour le statut de vérification de l'e-mail de l'utilisateur et affiche un message de succès
    public function verifyEmail($email_verification_token): RedirectResponse
    {
        // Rechercher l'utilisateur correspondant au token de vérification
        $user = User::where('email_verification_token', $email_verification_token)->first();

        // si l'utilisateur n'existe pas, rediriger vers la page de connexion avec un message d'erreur
        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Lien de vérification invalide ou utilisateur introuvable.');
        }

        // Vérifier si le lien de vérification a expiré
        if (now()->greaterThan($user->email_verification_expires_at)) {
            return redirect()->route('register')
                ->with('error', 'Le lien de vérification a expiré. Veuillez vous inscrire à nouveau.');
        }

        // Vérifier si l'e-mail de l'utilisateur a déjà été vérifié
        if ($user->email_verified) {
            return redirect()->route('login')
                ->with('info', 'Votre adresse e-mail a déjà été vérifiée. Vous pouvez vous connecter.');
        }

        // Mettre à jour le statut de vérification de l'e-mail de l'utilisateur
        $user->update([
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Votre adresse e-mail a été vérifiée avec succès ! Vous pouvez maintenant vous connecter.');
    }

    // Permet de renvoyer un e-mail de vérification à l'utilisateur si son e-mail n'a pas encore été vérifié
    public function resendVerificationEmail(VerifyMailRequest $request): RedirectResponse
    {
        // Valider l'adresse e-mail fournie par l'utilisateur
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Rechercher l'utilisateur correspondant à l'adresse e-mail fournie
        $user = User::where('email', $validated['email'])->firstOrFail();

        // Vérifier si l'e-mail de l'utilisateur a déjà été vérifié
        if ($user->email_verified) {
            return redirect()->route('login')
                ->with('info', 'Votre adresse e-mail a déjà été vérifiée. Vous pouvez vous connecter.');
        }

        // Générer un nouveau token de vérification et définir une nouvelle date d'expiration
        $user->update([
            'email_verification_token' => bin2hex(random_bytes(32)),
            'email_verification_expires_at' => now()->addHours(24),
        ]);

        // Générer l'URL de vérification avec le nouveau token
        $verificationUrl = route('verify.email', [
            'token' => $user->email_verification_token,
        ]);

        Mail::to($user->email)->send(new VerifyMail($user, $verificationUrl));

        return redirect()->route('login')
            ->with('success', 'Un nouvel e-mail de vérification vous a été envoyé.');
    }
}

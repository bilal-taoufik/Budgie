<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerifyMailController extends Controller
{
    // Met à jour le statut de vérification de l'e-mail de l'utilisateur et affiche un message de succès
    public function verifyEmail($email_verification_token)
    {
        $user = User::where('email_verification_token', $email_verification_token)->firstOrFail();

        if ($user->email_verified) {
            return redirect('login')->with('info', 'Votre adresse e-mail a déjà été vérifiée. Vous pouvez vous connecter.');
        } else {
            $user->email_verified = true;
            $user->save();

            return redirect('login')->with('success', 'Votre adresse e-mail a été vérifiée avec succès ! Vous pouvez maintenant vous connecter.');
        }
    }

    // Permet de renvoyer un e-mail de vérification à l'utilisateur si son e-mail n'a pas encore été vérifié
    public function resendVerificationEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        if ($user->email_verified) {
            return redirect()->route('login')
                ->with('info', 'Votre adresse e-mail a déjà été vérifiée. Vous pouvez vous connecter.');
        }

        $user->update([
            'email_verification_token' => bin2hex(random_bytes(32)),
            'email_verification_expires_at' => now()->addHours(24),
        ]);

        $verificationUrl = route('verify.email', [
            'token' => $user->email_verification_token,
        ]);

        Mail::to($user->email)->send(new VerifyMail($user, $verificationUrl));

        return redirect()->route('login')
            ->with('success', 'Un nouvel e-mail de vérification vous a été envoyé.');
    }
}

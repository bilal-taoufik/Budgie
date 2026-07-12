<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // Afficher le formulaire de connexion
    public function index(): View
    {
        return view('auth.login');
    }

    // Gérer une demande d'authentification entrante
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = Auth::user();
        if ($user->role === 'customer') {
            $this->rattraperTransactions($user);
        }
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    }

    // Déconnecter l'utilisateur authentifié
    public function delete(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function rattraperTransactions($user): void
    {
        foreach ($user->accounts as $account) {
            foreach ($account->transactions as $transaction) {
                if ($transaction->derniere_application) {
                    $debut = $transaction->derniere_application->copy()->addDay();
                } else {
                    $debut = $transaction->date_effet;
                }

                $fin = Carbon::today();

                $diff = $transaction->montantTotal($debut, $fin);

                if ($diff !== 0.0) {
                    $account->solde += $diff;
                    $account->save();
                }

                $transaction->derniere_application = $fin;
                $transaction->save();
            }
        }
    }
}

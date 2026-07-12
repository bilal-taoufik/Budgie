<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        // Récupérer les comptes de l'utilisateur authentifié
        $accounts = auth()->user()->accounts()->get();
        return view('customer.account', compact('accounts'));
    }

    // Fonction pour créer un nouveau compte
    public function store(AccountRequest $request): RedirectResponse
    {
        // Créer un nouveau compte pour l'utilisateur authentifié avec les données validées
        auth()->user()->accounts()->create($request->validated());
        return redirect()->route('customer.accounts.index')->with('success', 'Compte créé avec succès.');
    }

    // Fonction pour mettre à jour un compte existant
    public function update(AccountRequest $request, $account): RedirectResponse
    {
        // Récupérer le compte de l'utilisateur authentifié et s'assurer qu'il existe
        $account = auth()->user()->accounts()->findOrFail($account);
        $account->update($request->validated());
        return redirect()->route('customer.accounts.index')->with('success', 'Compte mis à jour avec succès.');
    }

    public function delete($account): RedirectResponse
    {
        // Récupérer le compte de l'utilisateur authentifié et s'assurer qu'il existe
        $account = auth()->user()->accounts()->findOrFail($account);
        $account->delete();
        return redirect()->route('customer.accounts.index')->with('success', 'Compte supprimé avec succès.');
    }
}

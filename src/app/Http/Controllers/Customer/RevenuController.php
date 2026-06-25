<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RevenuRequest;
use App\Models\Revenu;

class RevenuController extends Controller
{
    // affichage de la liste
    public function index()
    {
        // recupere uniquement les revenus des comptes appartenant a l'utilisateur
        $revenues = Revenu::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->with('account')->get(); //eviter les requêtes N+1

        // recupere les comptes
        $accounts = auth()->user()->accounts;

        return view('customer.revenu', compact('revenues', 'accounts'));
    }

    // Creer un revenu
    public function store(RevenuRequest $request)
    {
        // vzerifie que le compte appartient bien a l'utilisateur connecté
        $account = auth()->user()->accounts()->findOrFail($request->account_id);

        // Creer le revenu
        Revenu::create($request->validated());

        // augmente le solde du compte
        $account->increment('solde', $request->revenue_montant);

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu créé avec succès.');
    }

    // mise a jour d'un revenu
    public function update(RevenuRequest $request, $revenu)
    {
        // Vérifie que le revenu appartient bien a un compte de l'utilisateur
        $revenu = Revenu::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($revenu);

        // recupere le compte associé
        $account = auth()->user()->accounts()->findOrFail($request->account_id);

        // calcule la différence entre le nouveau montant et l'ancien
        $difference = $request->montant - $revenu->revenue_montant;

        $revenu->update($request->validated());

        // Ajuste le solde selon la différence
        $account->increment('solde', $difference);

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu mis à jour avec succès.');
    }

    // supprime un revenu 
    public function delete($revenu)
    {
        // verifie que le revenu appartient bien au compte du bon l'utilisateur
        $revenu = Revenu::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($revenu);

        // recupere le compte lié via BelongsTo
        $account = $revenu->account;

        // deduit le montant du compte 
        $account->decrement('solde', $revenu->revenue_montant);

        $revenu->delete();

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu supprimé et solde ajusté.');
    }
}
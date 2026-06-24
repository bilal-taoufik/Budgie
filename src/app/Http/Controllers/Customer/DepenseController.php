<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\DepenseRequest;
use App\Models\Depense;

class DepenseController extends Controller
{
    public function index()
    {
        // Récupère uniquement les dépenses des comptes de l'utilisateur connecté
        $depenses = Depense::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->with('account')->get();

        $accounts = auth()->user()->accounts;

        return view('customer.depense', compact('depenses', 'accounts'));
    }

    public function store(DepenseRequest $request)
    {
        // Vérifie que le compte appartient bien à l'utilisateur
        $account = auth()->user()->accounts()->findOrFail($request->account_id);

        Depense::create($request->validated());

        // déduit le montant du solde du compte
    $account->decrement('solde', $request->montant);

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Dépense créée avec succès');
    }

    public function update(DepenseRequest $request, $depense)
    {
        $depense = Depense::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($depense);

        // recupere le compte lie a la dépense pour pouvoir modifier son solde
        $account = auth()->user()->accounts()->findOrFail($request->account_id);

        // Calcule la différence entre le nouveau montant et l'ancien
        $difference = $request->montant - $depense->montant;

        // verifie que le solde est suffisant pour couvrir la différence
        if ($account->solde < $difference) {
            return redirect()->route('customer.depenses.index')
                ->with('error', 'Solde insuffisant pour modifier cette dépense');
        }

        $depense->update($request->validated());

        // ajuste le solde du compte selon la difference calculee
        $account->decrement('solde', $difference);

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Dépense mise à jour avec succès');
    }

    public function delete($depense)
    {
        $depense = Depense::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($depense);

        // rcupère le compte lie à cette dépense via la relation BelongsTo
        $account = $depense->account;

        // rembourse le montant de la dépense supprimée sur le solde du compte
        $account->increment('solde', $depense->montant);

        $depense->delete();

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Dépense supprimée et solde remboursé');
    }
}
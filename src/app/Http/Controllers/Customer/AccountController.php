<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\AccountRequest;
use App\Models\Account;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()->accounts;
        return view('customer.account', compact('accounts'));
    }

    // Fonction pour afficher le formulaire de création d'un compte
    public function store(AccountRequest $request)
    {
        auth()->user()->accounts()->create($request->validated());
        return redirect()->route('customer.accounts.index')->with('success', 'Compte créé avec succès.');
    }

    public function update(AccountRequest $request, $account)
    {
        $account = auth()->user()->accounts()->findOrFail($account); // Assurez-vous que l'utilisateur possède le compte
        $account->update($request->validated());
        return redirect()->route('customer.accounts.index')->with('success', 'Compte mis à jour avec succès.');
    }

    public function delete($account)
    {
        $account = auth()->user()->accounts()->findOrFail($account);
        $account->delete();
        return redirect()->route('customer.accounts.index')->with('success', 'Compte supprimé avec succès.');
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\TransactionRequest;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function revenus(): View
    {
        $accounts = auth()->user()->accounts()->get();
        $transactions = Transaction::with('account')
            ->whereIn('account_id', $accounts->pluck('id'))
            ->where('type', 'revenu')
            ->latest()
            ->get();

        return view('customer.revenu', compact('accounts', 'transactions'));
    }

    public function depenses(): View
    {
        $accounts = auth()->user()->accounts()->get();
        $transactions = Transaction::with('account')
            ->whereIn('account_id', $accounts->pluck('id'))
            ->where('type', 'depense')
            ->latest()
            ->get();

        return view('customer.depense', compact('accounts', 'transactions'));
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        // recupere le compte choisis d'ajouter la transaction
        $account = auth()->user()->accounts()->findOrFail($request->account_id);
        // on crée la transaction si les données reçus sont bonne via la request
        $transaction = $account->transactions()->create($request->validated());

        // applique la diff entre la date d'effet et aujoud'hui et la différence sur le solde
        $this->appliquerDiffSolde($account, $this->totalAppliqueJusquaAujourdhui($transaction));
        $transaction->update(['derniere_application' => Carbon::today()]);

        return redirect()->route($this->typeRoute($transaction->type))->with('success', 'Transaction creee.');
    }

    public function update(TransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $ancienCompte = auth()->user()->accounts()->findOrFail($transaction->account_id);
        $ancienTotal = $this->totalAppliqueJusquaAujourdhui($transaction);

        $transaction->update($request->validated());
        $transaction->refresh();

        $nouveauCompte = auth()->user()->accounts()->findOrFail($transaction->account_id);
        $nouveauTotal = $this->totalAppliqueJusquaAujourdhui($transaction);

        if ($ancienCompte->is($nouveauCompte)) {
            $this->appliquerDiffSolde($ancienCompte, $nouveauTotal - $ancienTotal);
        } else {
            $this->appliquerDiffSolde($ancienCompte, -$ancienTotal);
            $this->appliquerDiffSolde($nouveauCompte, $nouveauTotal);
        }

        return redirect()->route($this->typeRoute($transaction->type))->with('success', 'Transaction modifiee.');
    }

    public function delete(Transaction $transaction): RedirectResponse
    {
        $account = auth()->user()->accounts()->findOrFail($transaction->account_id);
        $type = $transaction->type;

        $this->appliquerDiffSolde($account, -$this->totalAppliqueJusquaAujourdhui($transaction));
        $transaction->delete();

        return redirect()->route($this->typeRoute($type))->with('success', 'Transaction supprimee.');
    }

    // methode qui dit que le montant total est appliquer de la date a effet et aujoud'hui
    private function totalAppliqueJusquaAujourdhui(Transaction $transaction): float
    {
        return $transaction->montantTotal($transaction->date_effet, Carbon::today());
    }

    // methode qui permet d'appliquer le montant trouver au solde
    private function appliquerDiffSolde(Account $account, float $diff): void
    {
        if ($diff === 0.0) {
            return;
        }

        $account->solde += $diff;
        $account->save();
    }

    private function typeRoute(string $type): string
    {
        if ($type === 'revenu') {
            return 'customer.revenues.index';
        }

        return 'customer.depenses.index';
    }
}
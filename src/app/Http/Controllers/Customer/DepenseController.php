<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\DepenseRequest;
use App\Models\Depense;
use Carbon\Carbon;

class DepenseController extends Controller
{
    public function index()
    {
        $this->appliquerDepensesEchues();

        $accounts = auth()->user()->accounts;
        $accountIds = $accounts->pluck('id');

        $depenses = Depense::whereIn('account_id', $accountIds)
            ->with('account')
            ->get();

        $taxDepenses = [];

        foreach ($accounts as $account) {
            $interetsAnnuels = max(0, (float) $account->solde) * ((float) $account->interest_rate / 100);
            $taxes = $interetsAnnuels * ((float) $account->tax_rate / 100);

            if ($taxes > 0) {
                $taxDepenses[] = [
                    'account' => $account,
                    'interets' => $interetsAnnuels,
                    'montant' => $taxes,
                ];
            }
        }

        return view('customer.depense', compact('depenses', 'accounts', 'taxDepenses'));
    }

    public function store(DepenseRequest $request)
    {
        auth()->user()->accounts()->findOrFail($request->account_id);

        $depense = Depense::create($request->validated());
        $this->appliquerDepenseEchue($depense);

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Depense creee avec succes');
    }

    public function update(DepenseRequest $request, $depense)
    {
        $depense = Depense::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->findOrFail($depense);

        auth()->user()->accounts()->findOrFail($request->account_id);

        $depense->update($request->validated());
        $this->appliquerDepenseEchue($depense->fresh('account'));

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Depense mise a jour avec succes');
    }

    public function delete($depense)
    {
        $depense = Depense::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->findOrFail($depense);

        $depense->delete();

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Depense supprimee');
    }

    private function appliquerDepensesEchues(): void
    {
        $depenses = Depense::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->with('account')
            ->get();

        foreach ($depenses as $depense) {
            $this->appliquerDepenseEchue($depense);
        }
    }

    private function appliquerDepenseEchue(Depense $depense): void
    {
        $datePaiement = $this->prochaineDatePaiement($depense);
        $aujourdhui = now()->startOfDay();

        while ($datePaiement && $datePaiement->lessThanOrEqualTo($aujourdhui)) {
            $depense->account->decrement('solde', $depense->montant);
            $depense->last_debited_at = $datePaiement->toDateString();
            $depense->save();

            if ($depense->fractionnement === 'unique') {
                break;
            }

            $datePaiement = $this->prochaineDatePaiement($depense);
        }
    }

    private function prochaineDatePaiement(Depense $depense): ?Carbon
    {
        if ($depense->fractionnement === 'unique' && $depense->last_debited_at) {
            return null;
        }

        $date = Carbon::parse($depense->date_effet)->startOfDay();

        if (! $depense->last_debited_at) {
            return $date;
        }

        $derniereDate = Carbon::parse($depense->last_debited_at)->startOfDay();

        while ($date->lessThanOrEqualTo($derniereDate)) {
            $date = $this->dateSuivante($date, $depense->fractionnement);
        }

        return $date;
    }

    private function dateSuivante(Carbon $date, string $fractionnement): Carbon
    {
        if ($fractionnement === 'mensuel') {
            return $date->copy()->addMonthNoOverflow();
        }

        if ($fractionnement === 'semestriel') {
            return $date->copy()->addMonthsNoOverflow(6);
        }

        if ($fractionnement === 'annuel') {
            return $date->copy()->addYearNoOverflow();
        }

        return $date->copy();
    }
}
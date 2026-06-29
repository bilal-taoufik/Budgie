<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RevenuRequest;
use App\Models\Revenu;
use Carbon\Carbon;

class RevenuController extends Controller
{
    public function index()
    {
        $this->appliquerRevenusEchus();

        $accounts = auth()->user()->accounts;
        $accountIds = $accounts->pluck('id');

        $revenues = Revenu::whereIn('account_id', $accountIds)
            ->with('account')
            ->get();

        $interestRevenues = [];

        foreach ($accounts as $account) {
            $interetsAnnuels = max(0, (float) $account->solde) * ((float) $account->interest_rate / 100);
            $taxes = $interetsAnnuels * ((float) $account->tax_rate / 100);

            if ($interetsAnnuels > 0) {
                $interestRevenues[] = [
                    'account' => $account,
                    'montant' => $interetsAnnuels,
                    'taxes' => $taxes,
                    'net' => $interetsAnnuels - $taxes,
                ];
            }
        }

        return view('customer.revenu', compact('revenues', 'accounts', 'interestRevenues'));
    }

    public function store(RevenuRequest $request)
    {
        auth()->user()->accounts()->findOrFail($request->account_id);

        $revenu = Revenu::create($request->validated());
        $this->appliquerRevenuEchu($revenu);

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu cree avec succes.');
    }

    public function update(RevenuRequest $request, $revenu)
    {
        $revenu = Revenu::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->findOrFail($revenu);

        auth()->user()->accounts()->findOrFail($request->account_id);

        $revenu->update($request->validated());
        $this->appliquerRevenuEchu($revenu->fresh('account'));

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu mis a jour avec succes.');
    }

    public function delete($revenu)
    {
        $revenu = Revenu::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->findOrFail($revenu);

        $revenu->delete();

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu supprime.');
    }

    private function appliquerRevenusEchus(): void
    {
        $revenus = Revenu::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->with('account')
            ->get();

        foreach ($revenus as $revenu) {
            $this->appliquerRevenuEchu($revenu);
        }
    }

    private function appliquerRevenuEchu(Revenu $revenu): void
    {
        $datePaiement = $this->prochaineDatePaiement($revenu);
        $aujourdhui = now()->startOfDay();

        while ($datePaiement && $datePaiement->lessThanOrEqualTo($aujourdhui)) {
            $revenu->account->increment('solde', $revenu->revenu_montant);
            $revenu->last_credited_at = $datePaiement->toDateString();
            $revenu->save();

            if ($revenu->revenu_fractionnement === 'unique') {
                break;
            }

            $datePaiement = $this->prochaineDatePaiement($revenu);
        }
    }

    private function prochaineDatePaiement(Revenu $revenu): ?Carbon
    {
        if ($revenu->revenu_fractionnement === 'unique' && $revenu->last_credited_at) {
            return null;
        }

        $date = Carbon::parse($revenu->revenu_date_effet)->startOfDay();

        if (! $revenu->last_credited_at) {
            return $date;
        }

        $derniereDate = Carbon::parse($revenu->last_credited_at)->startOfDay();

        while ($date->lessThanOrEqualTo($derniereDate)) {
            $date = $this->dateSuivante($date, $revenu->revenu_fractionnement);
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
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RevenuRequest;
use App\Models\Revenu;
use Carbon\Carbon;

class RevenuController extends Controller
{
    // Affichage de la liste
    public function index()
    {
        $this->appliquerRevenusEchus();

        // Recupere uniquement les revenus des comptes appartenant a l'utilisateur
        $revenues = Revenu::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->with('account')->get();

        $accounts = auth()->user()->accounts;

        return view('customer.revenu', compact('revenues', 'accounts'));
    }

    // Creer un revenu
    public function store(RevenuRequest $request)
    {
        // Verifie que le compte appartient bien a l'utilisateur connecte
        auth()->user()->accounts()->findOrFail($request->account_id);

        $revenu = Revenu::create($request->validated());

        // Credite uniquement si une echeance est deja arrivee.
        $this->appliquerRevenuEchu($revenu);

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu cree avec succes.');
    }

    // Mise a jour d'un revenu
    public function update(RevenuRequest $request, $revenu)
    {
        $revenu = Revenu::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($revenu);

        auth()->user()->accounts()->findOrFail($request->account_id);

        $revenu->update($request->validated());

        $this->appliquerRevenuEchu($revenu->fresh('account'));

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu mis a jour avec succes.');
    }

    // Supprime un revenu
    public function delete($revenu)
    {
        $revenu = Revenu::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($revenu);

        // La suppression arrete les prochaines echeances, sans retirer les revenus deja recus.
        $revenu->delete();

        return redirect()->route('customer.revenues.index')
            ->with('success', 'Revenu supprime.');
    }

    private function appliquerRevenusEchus(): void
    {
        Revenu::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->with('account')
            ->get()
            ->each(fn (Revenu $revenu) => $this->appliquerRevenuEchu($revenu));
    }

    private function appliquerRevenuEchu(Revenu $revenu): void
    {
        $today = now()->startOfDay();
        $nextDate = $this->prochaineDatePaiement($revenu);

        while ($nextDate && $nextDate->lessThanOrEqualTo($today)) {
            $revenu->account->increment('solde', $revenu->revenue_montant);

            $revenu->last_credited_at = $nextDate->toDateString();
            $revenu->save();

            if ($revenu->revenue_fractionnement === 'une_fois') {
                break;
            }

            $nextDate = $this->prochaineDatePaiement($revenu);
        }
    }

    private function prochaineDatePaiement(Revenu $revenu): ?Carbon
    {
        if ($revenu->revenue_fractionnement === 'une_fois' && $revenu->last_credited_at) {
            return null;
        }

        $date = Carbon::parse($revenu->revenue_date_effet)->startOfDay();

        if (! $revenu->last_credited_at) {
            return $date;
        }

        $lastCreditedAt = Carbon::parse($revenu->last_credited_at)->startOfDay();

        while ($date->lessThanOrEqualTo($lastCreditedAt)) {
            $date = $this->dateSuivante($date, $revenu->revenue_fractionnement);
        }

        return $date;
    }

    private function dateSuivante(Carbon $date, string $fractionnement): Carbon
    {
        return match ($fractionnement) {
            'mensuel' => $date->copy()->addMonthNoOverflow(),
            'semestriel' => $date->copy()->addMonthsNoOverflow(6),
            'annuel' => $date->copy()->addYearNoOverflow(),
            default => $date->copy(),
        };
    }
}
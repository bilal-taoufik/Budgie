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

        // Recupere uniquement les depenses des comptes de l'utilisateur connecte
        $depenses = Depense::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->with('account')->get();

        $accounts = auth()->user()->accounts;

        return view('customer.depense', compact('depenses', 'accounts'));
    }

    public function store(DepenseRequest $request)
    {
        // Verifie que le compte appartient bien a l'utilisateur
        auth()->user()->accounts()->findOrFail($request->account_id);

        $depense = Depense::create($request->validated());

        // Debite uniquement si une echeance est deja arrivee.
        $this->appliquerDepenseEchue($depense);

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Depense creee avec succes');
    }

    public function update(DepenseRequest $request, $depense)
    {
        $depense = Depense::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($depense);

        auth()->user()->accounts()->findOrFail($request->account_id);

        $depense->update($request->validated());

        $this->appliquerDepenseEchue($depense->fresh('account'));

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Depense mise a jour avec succes');
    }

    public function delete($depense)
    {
        $depense = Depense::whereIn(
            'account_id',
            auth()->user()->accounts->pluck('id')
        )->findOrFail($depense);

        // La suppression arrete les prochaines echeances, sans rembourser les paiements deja passes.
        $depense->delete();

        return redirect()->route('customer.depenses.index')
            ->with('success', 'Depense supprimee');
    }

    private function appliquerDepensesEchues(): void
    {
        Depense::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->with('account')
            ->get()
            ->each(fn (Depense $depense) => $this->appliquerDepenseEchue($depense));
    }

    private function appliquerDepenseEchue(Depense $depense): void
    {
        $today = now()->startOfDay();
        $nextDate = $this->prochaineDatePaiement($depense);

        while ($nextDate && $nextDate->lessThanOrEqualTo($today)) {
            $depense->account->decrement('solde', $depense->montant);

            $depense->last_debited_at = $nextDate->toDateString();
            $depense->save();

            if ($depense->fractionnement === 'une_fois') {
                break;
            }

            $nextDate = $this->prochaineDatePaiement($depense);
        }
    }

    private function prochaineDatePaiement(Depense $depense): ?Carbon
    {
        if ($depense->fractionnement === 'une_fois' && $depense->last_debited_at) {
            return null;
        }

        $date = Carbon::parse($depense->date_effet)->startOfDay();

        if (! $depense->last_debited_at) {
            return $date;
        }

        $lastDebitedAt = Carbon::parse($depense->last_debited_at)->startOfDay();

        while ($date->lessThanOrEqualTo($lastDebitedAt)) {
            $date = $this->dateSuivante($date, $depense->fractionnement);
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
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrevisionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date_prevision' => ['nullable', 'date'],
        ]);

        if ($request->filled('date_prevision')) {
            $selectedDate = Carbon::parse($request->date_prevision)->startOfDay();
        } else {
            $selectedDate = now()->startOfDay();
        }

        $accounts = Account::where('user_id', auth()->id())
            ->with(['revenus', 'depenses'])
            ->get();

        $previsions = [];
        $totalPrevision = 0;

        foreach ($accounts as $account) {
            $prevision = [
                'account' => $account,
                'lignes' => $this->lignesDesSixProchainsMois($account),
                'date' => $this->previsionPourUneDate($account, $selectedDate),
            ];

            $previsions[] = $prevision;
            $totalPrevision += $prevision['date']['solde'];
        }

        return view('customer.prevision', compact(
            'previsions',
            'selectedDate',
            'totalPrevision'
        ));
    }

    private function lignesDesSixProchainsMois(Account $account): array
    {
        $lignes = [];
        $solde = (float) $account->solde;
        $premierMois = now()->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $debutMois = $premierMois->copy()->addMonthsNoOverflow($i)->startOfMonth();
            $finMois = $debutMois->copy()->endOfMonth();

            $mouvements = $this->mouvementsEntreDeuxDates($account, $debutMois, $finMois);
            $interets = $this->interetsDuMois($account, $solde, $debutMois);
            $variation = $mouvements['revenus'] - $mouvements['depenses'] + $interets['net'];
            $solde += $variation;

            $lignes[] = [
                'mois' => $debutMois->translatedFormat('F Y'),
                'revenus' => $mouvements['revenus'],
                'depenses' => $mouvements['depenses'],
                'interets' => $interets['brut'],
                'taxes' => $interets['taxe'],
                'interets_net' => $interets['net'],
                'variation' => $variation,
                'solde' => $solde,
            ];
        }

        return $lignes;
    }

    private function previsionPourUneDate(Account $account, Carbon $selectedDate): array
    {
        $aujourdhui = now()->startOfDay();

        if ($selectedDate->lessThan($aujourdhui)) {
            return [
                'revenus' => 0,
                'depenses' => 0,
                'interets' => 0,
                'taxes' => 0,
                'interets_net' => 0,
                'variation' => 0,
                'solde' => (float) $account->solde,
            ];
        }

        $mouvements = $this->mouvementsEntreDeuxDates($account, $aujourdhui, $selectedDate);
        $interets = $this->interetsEntreDeuxDates($account, (float) $account->solde, $aujourdhui, $selectedDate);
        $variation = $mouvements['revenus'] - $mouvements['depenses'] + $interets['net'];

        return [
            'revenus' => $mouvements['revenus'],
            'depenses' => $mouvements['depenses'],
            'interets' => $interets['brut'],
            'taxes' => $interets['taxe'],
            'interets_net' => $interets['net'],
            'variation' => $variation,
            'solde' => (float) $account->solde + $variation,
        ];
    }

    private function mouvementsEntreDeuxDates(Account $account, Carbon $start, Carbon $end): array
    {
        $revenus = 0;
        $depenses = 0;

        foreach ($account->revenus as $revenu) {
            $revenus += $this->totalSurPeriode(
                Carbon::parse($revenu->revenu_date_effet),
                $revenu->last_credited_at ? Carbon::parse($revenu->last_credited_at) : null,
                $revenu->revenu_fractionnement,
                (float) $revenu->revenu_montant,
                $start,
                $end
            );
        }

        foreach ($account->depenses as $depense) {
            $depenses += $this->totalSurPeriode(
                Carbon::parse($depense->date_effet),
                $depense->last_debited_at ? Carbon::parse($depense->last_debited_at) : null,
                $depense->fractionnement,
                (float) $depense->montant,
                $start,
                $end
            );
        }

        return [
            'revenus' => $revenus,
            'depenses' => $depenses,
        ];
    }

    private function interetsDuMois(Account $account, float $solde, Carbon $mois): array
    {
        // Les interets et les taxes sont prevus en decembre seulement.
        if ((int) $mois->format('m') !== 12) {
            return ['brut' => 0, 'taxe' => 0, 'net' => 0];
        }

        $brut = max(0, $solde) * ((float) $account->interest_rate / 100);
        $taxe = $brut * ((float) $account->tax_rate / 100);

        return [
            'brut' => $brut,
            'taxe' => $taxe,
            'net' => $brut - $taxe,
        ];
    }

    private function interetsEntreDeuxDates(Account $account, float $solde, Carbon $start, Carbon $end): array
    {
        $total = ['brut' => 0, 'taxe' => 0, 'net' => 0];
        $mois = $start->copy()->startOfMonth();

        while ($mois->lessThanOrEqualTo($end)) {
            $interets = $this->interetsDuMois($account, $solde, $mois);

            $total['brut'] += $interets['brut'];
            $total['taxe'] += $interets['taxe'];
            $total['net'] += $interets['net'];

            $solde += $interets['net'];
            $mois->addMonthNoOverflow();
        }

        return $total;
    }

    private function totalSurPeriode(Carbon $dateEffet, ?Carbon $derniereDate, string $fractionnement, float $montant, Carbon $start, Carbon $end): float
    {
        if ($end->lessThan($start)) {
            return 0;
        }

        if ($fractionnement === 'unique') {
            if ($derniereDate) {
                return 0;
            }

            return $dateEffet->betweenIncluded($start, $end) ? $montant : 0;
        }

        $date = $dateEffet->copy()->startOfDay();
        $total = 0;

        while ($date->lessThan($start)) {
            $date = $this->dateSuivante($date, $fractionnement);
        }

        while ($date->lessThanOrEqualTo($end)) {
            if (! $derniereDate || $date->greaterThan($derniereDate)) {
                $total += $montant;
            }

            $date = $this->dateSuivante($date, $fractionnement);
        }

        return $total;
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

        return $date->copy()->addCentury();
    }
}
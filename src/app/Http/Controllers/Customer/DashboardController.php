<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\Revenu;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $accounts = auth()->user()->accounts()->get();
        $accountIds = $accounts->pluck('id');

        $revenus = Revenu::whereIn('account_id', $accountIds)->with('account')->get();
        $depenses = Depense::whereIn('account_id', $accountIds)->with('account')->get();

        $soldeTotal = 0;
        $interetsAnnuels = 0;
        $taxesAnnuelles = 0;

        foreach ($accounts as $account) {
            $solde = (float) $account->solde;
            $interets = max(0, $solde) * ((float) $account->interest_rate / 100);
            $taxes = $interets * ((float) $account->tax_rate / 100);

            $soldeTotal += $solde;
            $interetsAnnuels += $interets;
            $taxesAnnuelles += $taxes;
        }

        $revenuCeMois = $this->totalRevenusDuMois($revenus, now());
        $depenseCeMois = $this->totalDepensesDuMois($depenses, now());

        // Les interets et taxes sont prevus une fois par an, le 31 decembre.
        if ((int) now()->format('m') === 12) {
            $revenuCeMois += $interetsAnnuels;
            $depenseCeMois += $taxesAnnuelles;
        }

        $labels = [];
        $monthlyRevenus = [];
        $monthlyDepenses = [];
        $balanceEvolution = [];
        $soldeDuGraphique = $soldeTotal;

        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i)->startOfMonth();
            $revenusDuMois = $this->totalRevenusDuMois($revenus, $mois);
            $depensesDuMois = $this->totalDepensesDuMois($depenses, $mois);

            if ((int) $mois->format('m') === 12) {
                $revenusDuMois += $interetsAnnuels;
                $depensesDuMois += $taxesAnnuelles;
            }

            $labels[] = ucfirst($mois->translatedFormat('M Y'));
            $monthlyRevenus[] = round($revenusDuMois, 2);
            $monthlyDepenses[] = round($depensesDuMois, 2);
            $balanceEvolution[] = round($soldeDuGraphique + $revenusDuMois - $depensesDuMois, 2);

            $soldeDuGraphique += $revenusDuMois - $depensesDuMois;
        }

        $depenseLabels = [];
        $depenseData = [];

        foreach ($depenses as $depense) {
            $depenseLabels[] = $depense->nom;
            $depenseData[] = (float) $depense->montant;
        }

        if ($taxesAnnuelles > 0) {
            $depenseLabels[] = 'Taxes annuelles';
            $depenseData[] = round($taxesAnnuelles, 2);
        }

        $charts = [
            'labels' => $labels,
            'balanceEvolution' => $balanceEvolution,
            'monthlyRevenus' => $monthlyRevenus,
            'monthlyDepenses' => $monthlyDepenses,
            'depenseLabels' => $depenseLabels,
            'depenseData' => $depenseData,
        ];

        $transactionsRecentes = $this->transactionsRecentes($revenus, $depenses, $accounts);

        return view('customer.dashboard', compact(
            'accounts',
            'soldeTotal',
            'revenuCeMois',
            'depenseCeMois',
            'interetsAnnuels',
            'taxesAnnuelles',
            'transactionsRecentes',
            'charts'
        ));
    }

    private function totalRevenusDuMois($revenus, Carbon $mois): float
    {
        $total = 0;

        foreach ($revenus as $revenu) {
            $total += $this->montantDuMois(
                (float) $revenu->revenu_montant,
                $revenu->revenu_fractionnement,
                Carbon::parse($revenu->revenu_date_effet),
                $mois
            );
        }

        return $total;
    }

    private function totalDepensesDuMois($depenses, Carbon $mois): float
    {
        $total = 0;

        foreach ($depenses as $depense) {
            $total += $this->montantDuMois(
                (float) $depense->montant,
                $depense->fractionnement,
                Carbon::parse($depense->date_effet),
                $mois
            );
        }

        return $total;
    }

    private function montantDuMois(float $montant, string $fractionnement, Carbon $dateEffet, Carbon $mois): float
    {
        $debutMois = $mois->copy()->startOfMonth();
        $finMois = $mois->copy()->endOfMonth();

        if ($dateEffet->greaterThan($finMois)) {
            return 0;
        }

        if ($fractionnement === 'unique') {
            return $dateEffet->betweenIncluded($debutMois, $finMois) ? $montant : 0;
        }

        $differenceEnMois = $dateEffet->copy()->startOfMonth()->diffInMonths($debutMois);

        if ($fractionnement === 'mensuel') {
            return $montant;
        }

        if ($fractionnement === 'semestriel' && $differenceEnMois % 6 === 0) {
            return $montant;
        }

        if ($fractionnement === 'annuel' && $differenceEnMois % 12 === 0) {
            return $montant;
        }

        return 0;
    }

    private function transactionsRecentes($revenus, $depenses, $accounts)
    {
        $transactions = [];

        foreach ($revenus as $revenu) {
            $transactions[] = [
                'type' => 'Revenu',
                'nom' => $revenu->revenu_nom,
                'compte' => $revenu->account?->name,
                'montant' => (float) $revenu->revenu_montant,
                'date' => $revenu->last_credited_at ?? $revenu->revenu_date_effet,
                'prevision' => false,
            ];
        }

        foreach ($depenses as $depense) {
            $transactions[] = [
                'type' => 'Depense',
                'nom' => $depense->nom,
                'compte' => $depense->account?->name,
                'montant' => -1 * (float) $depense->montant,
                'date' => $depense->last_debited_at ?? $depense->date_effet,
                'prevision' => false,
            ];
        }

        foreach ($accounts as $account) {
            $interets = max(0, (float) $account->solde) * ((float) $account->interest_rate / 100);
            $taxes = $interets * ((float) $account->tax_rate / 100);
            $dateFinAnnee = now()->endOfYear()->toDateString();

            if ($interets > 0) {
                $transactions[] = [
                    'type' => 'Prevision',
                    'nom' => 'Interets annuels',
                    'compte' => $account->name,
                    'montant' => round($interets, 2),
                    'date' => $dateFinAnnee,
                    'prevision' => true,
                ];
            }

            if ($taxes > 0) {
                $transactions[] = [
                    'type' => 'Prevision',
                    'nom' => 'Taxes annuelles',
                    'compte' => $account->name,
                    'montant' => -1 * round($taxes, 2),
                    'date' => $dateFinAnnee,
                    'prevision' => true,
                ];
            }
        }

        usort($transactions, fn ($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));

        return collect(array_slice($transactions, 0, 10));
    }
}
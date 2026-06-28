<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\Revenu;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $accounts = $user->accounts()->with(['depenses', 'revenus'])->get();
        $accountIds = $accounts->pluck('id');

        $depenses = Depense::whereIn('account_id', $accountIds)->with('account')->get();
        $revenus = Revenu::whereIn('account_id', $accountIds)->with('account')->get();

        $now = now();
        $soldeTotal = $accounts->sum('solde');
        $revenuCeMois = $revenus->sum(fn (Revenu $revenu) => $this->montantDuMoisRevenu($revenu, $now));
        $depenseCeMois = $depenses->sum(fn (Depense $depense) => $this->montantDuMoisDepense($depense, $now));

        $months = collect(CarbonPeriod::create(
            $now->copy()->subMonths(5)->startOfMonth(),
            '1 month',
            $now->copy()->startOfMonth()
        ));

        $monthlyRevenus = $months->map(fn (Carbon $month) => round($revenus->sum(
            fn (Revenu $revenu) => $this->montantDuMoisRevenu($revenu, $month)
        ), 2));

        $monthlyDepenses = $months->map(fn (Carbon $month) => round($depenses->sum(
            fn (Depense $depense) => $this->montantDuMoisDepense($depense, $month)
        ), 2));

        $monthlyNet = $monthlyRevenus->zip($monthlyDepenses)
            ->map(fn ($values) => $values[0] - $values[1]);

        $startBalance = $soldeTotal - $monthlyNet->sum();
        $runningBalance = $startBalance;
        $balanceEvolution = $monthlyNet->map(function (float $net) use (&$runningBalance) {
            $runningBalance += $net;
            return round($runningBalance, 2);
        });

        $transactionsRecentes = $this->transactionsRecentes($revenus, $depenses);

        $charts = [
            'labels' => $months->map(fn (Carbon $month) => ucfirst($month->translatedFormat('M Y')))->values(),
            'balanceEvolution' => $balanceEvolution->values(),
            'monthlyRevenus' => $monthlyRevenus->values(),
            'monthlyDepenses' => $monthlyDepenses->values(),
            'depenseLabels' => $depenses->pluck('nom')->values(),
            'depenseData' => $depenses->map(fn (Depense $depense) => (float) $depense->montant)->values(),
        ];

        return view('customer.dashboard', compact(
            'accounts',
            'soldeTotal',
            'revenuCeMois',
            'depenseCeMois',
            'transactionsRecentes',
            'charts'
        ));
    }

    private function montantDuMoisRevenu(Revenu $revenu, Carbon $month): float
    {
        return $this->montantDuMois(
            (float) $revenu->revenu_montant,
            $revenu->revenu_fractionnement,
            Carbon::parse($revenu->revenu_date_effet),
            $month
        );
    }

    private function montantDuMoisDepense(Depense $depense, Carbon $month): float
    {
        return $this->montantDuMois(
            (float) $depense->montant,
            $depense->fractionnement,
            Carbon::parse($depense->date_effet),
            $month
        );
    }

    private function montantDuMois(float $montant, string $fractionnement, Carbon $dateEffet, Carbon $month): float
    {
        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        if ($dateEffet->greaterThan($monthEnd)) {
            return 0;
        }

        $monthsDiff = $dateEffet->copy()->startOfMonth()->diffInMonths($monthStart);

        return match ($fractionnement) {
            'mensuel' => $montant,
            'semestriel' => $monthsDiff % 6 === 0 ? $montant : 0,
            'annuel' => $monthsDiff % 12 === 0 ? $montant : 0,
            'unique' => $dateEffet->betweenIncluded($monthStart, $monthEnd) ? $montant : 0,
            default => 0,
        };
    }

    private function transactionsRecentes(Collection $revenus, Collection $depenses): Collection
    {
        $revenuTransactions = $revenus->map(fn (Revenu $revenu) => [
            'type' => 'Revenu',
            'nom' => $revenu->revenu_nom,
            'compte' => $revenu->account?->name,
            'montant' => (float) $revenu->revenu_montant,
            'date' => $revenu->last_credited_at ?? $revenu->revenu_date_effet,
        ]);

        $depenseTransactions = $depenses->map(fn (Depense $depense) => [
            'type' => 'Depense',
            'nom' => $depense->nom,
            'compte' => $depense->account?->name,
            'montant' => -1 * (float) $depense->montant,
            'date' => $depense->last_debited_at ?? $depense->date_effet,
        ]);

        return $revenuTransactions
            ->merge($depenseTransactions)
            ->sortByDesc('date')
            ->take(8)
            ->values();
    }
}


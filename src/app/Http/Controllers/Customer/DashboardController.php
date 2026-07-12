<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $comptes = auth()->user()->accounts()->get();
        $accountIds = $comptes->pluck('id');
        $transactions = Transaction::with('account')
            ->whereIn('account_id', $accountIds)
            ->get();

        $soldeTotal = $comptes->sum('solde');
        $revenuTotal = $this->totalDansMois($transactions, 'revenu', now());
        $depenseTotal = $this->totalDansMois($transactions, 'depense', now());
        $derniersComptes = $comptes->sortByDesc('created_at')->take(3);
        $graphiques = $this->construireGraphiques($transactions, $soldeTotal);
        $listeTransactions = $this->transactionsRecentes($transactions);

        return view('customer.dashboard', compact(
            'comptes', 'soldeTotal', 'revenuTotal', 'depenseTotal',
            'derniersComptes', 'graphiques', 'listeTransactions'
        ));
    }

    private function construireGraphiques(Collection $transactions, float $soldeTotal): array
    {
        $derniersMois = collect(range(5, 0))->map(fn ($indexMois) => now()->subMonths($indexMois));
        $revenusMensuels = $derniersMois->map(fn ($date) => $this->totalDansMois($transactions, 'revenu', $date))->values();
        $depensesMensuelles = $derniersMois->map(fn ($date) => $this->totalDansMois($transactions, 'depense', $date))->values();
        $mouvementsMensuels = $revenusMensuels->zip($depensesMensuelles)
            ->map(fn ($totaux) => $totaux[0] - $totaux[1]);

        $soldeDepart = $soldeTotal - $mouvementsMensuels->sum();
        $evolutionSolde = $mouvementsMensuels->map(function ($mouvement) use (&$soldeDepart) {
            $soldeDepart += $mouvement;
            return round($soldeDepart, 2);
        })->values();

        $depensesParNom = $this->occurrencesEntre($transactions->where('type', 'depense'), now()->startOfMonth(), now())
            ->groupBy('nom')
            ->map(fn ($occurrences) => $occurrences->sum('montant'));

        return [
            'etiquettes' => $derniersMois->map(fn ($date) => $date->translatedFormat('M Y'))->values(),
            'revenusMensuels' => $revenusMensuels,
            'depensesMensuelles' => $depensesMensuelles,
            'evolutionSolde' => $evolutionSolde,
            'etiquettesDepenses' => $depensesParNom->keys()->values(),
            'donneesDepenses' => $depensesParNom->values(),
        ];
    }

    private function totalDansMois(Collection $transactions, string $type, Carbon $mois): float
    {
        $debut = $mois->copy()->startOfMonth();

        if ($mois->isSameMonth(now()) && $mois->isSameYear(now())) {
            $fin = now();
        } else {
            $fin = $mois->copy()->endOfMonth();
        }

        return abs($transactions->where('type', $type)->sum(fn ($transaction) => $transaction->montantTotal($debut, $fin)));
    }

    private function transactionsRecentes(Collection $transactions): LengthAwarePaginator
    {
        $dateDebut = $transactions->min('date_effet');

        if ($dateDebut === null) {
            $dateDebut = now();
        }

        $occurrences = $this->occurrencesEntre($transactions, $dateDebut, now())
            ->sortByDesc('date_effet')
            ->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $parPage = 5;

        return new LengthAwarePaginator(
            $occurrences->forPage($page, $parPage)->values(),
            $occurrences->count(),
            $parPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function occurrencesEntre(Collection $transactions, Carbon|string $debut, Carbon|string $fin): Collection
    {
        if ($debut instanceof Carbon) {
            $debut = $debut;
        } else {
            $debut = Carbon::parse($debut);
        }

        if ($fin instanceof Carbon) {
            $fin = $fin;
        } else {
            $fin = Carbon::parse($fin);
        }

        return $transactions->flatMap(function (Transaction $transaction) use ($debut, $fin) {
            return $transaction->nrbEcheance($debut, $fin)->map(fn (Carbon $date) => (object) [
                'nom' => $transaction->nom,
                'type' => $transaction->type,
                'montant' => (float) $transaction->montant,
                'date_effet' => $date,
                'account' => $transaction->account,
            ]);
        });
    }
}
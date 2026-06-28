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

        $selectedDate = $request->filled('date_prevision')
            ? Carbon::parse($request->date_prevision)->startOfDay()
            : now()->startOfDay();

        $accounts = Account::where('user_id', auth()->id())
            ->with(['revenus', 'depenses'])
            ->get();

        $previsions = $accounts->map(
            fn (Account $account) => $this->previsionCompte($account, $selectedDate)
        );

        return view('customer.prevision', [
            'previsions' => $previsions,
            'selectedDate' => $selectedDate,
        ]);
    }

    private function previsionCompte(Account $account, Carbon $selectedDate): array
    {
        return [
            'account' => $account,
            'lignes' => $this->previsionMensuelle($account),
            'date' => $this->previsionALaDate($account, $selectedDate),
        ];
    }

    private function previsionMensuelle(Account $account): array
    {
        $solde = (float) $account->solde;
        $start = now()->startOfMonth();

        return collect(range(0, 5))->map(function (int $index) use ($account, &$solde, $start) {
            $monthStart = $start->copy()->addMonthsNoOverflow($index)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $mouvements = $this->mouvementsSurPeriode($account, $monthStart, $monthEnd);

            $solde += $mouvements['variation'];

            return [
                'mois' => $monthStart->translatedFormat('F Y'),
                'revenus' => $mouvements['revenus'],
                'depenses' => $mouvements['depenses'],
                'variation' => $mouvements['variation'],
                'solde' => $solde,
            ];
        })->all();
    }

    private function previsionALaDate(Account $account, Carbon $selectedDate): array
    {
        $today = now()->startOfDay();
        $mouvements = $selectedDate->greaterThanOrEqualTo($today)
            ? $this->mouvementsSurPeriode($account, $today, $selectedDate)
            : ['revenus' => 0.0, 'depenses' => 0.0, 'variation' => 0.0];

        return [
            ...$mouvements,
            'solde' => (float) $account->solde + $mouvements['variation'],
        ];
    }

    private function mouvementsSurPeriode(Account $account, Carbon $start, Carbon $end): array
    {
        $revenus = $account->revenus->sum(fn ($revenu) => $this->montantSurPeriode(
            Carbon::parse($revenu->revenu_date_effet),
            $revenu->last_credited_at ? Carbon::parse($revenu->last_credited_at) : null,
            $revenu->revenu_fractionnement,
            (float) $revenu->revenu_montant,
            $start,
            $end
        ));

        $depenses = $account->depenses->sum(fn ($depense) => $this->montantSurPeriode(
            Carbon::parse($depense->date_effet),
            $depense->last_debited_at ? Carbon::parse($depense->last_debited_at) : null,
            $depense->fractionnement,
            (float) $depense->montant,
            $start,
            $end
        ));

        return [
            'revenus' => $revenus,
            'depenses' => $depenses,
            'variation' => $revenus - $depenses,
        ];
    }

    private function montantSurPeriode(Carbon $dateEffet, ?Carbon $lastAppliedAt, string $fractionnement, float $montant, Carbon $start, Carbon $end): float
    {
        $date = $dateEffet->copy()->startOfDay();
        $total = 0.0;

        if ($end->lessThan($start)) {
            return 0.0;
        }

        if ($fractionnement === 'unique') {
            if ($lastAppliedAt) {
                return 0.0;
            }

            return $date->betweenIncluded($start, $end) ? $montant : 0.0;
        }

        while ($date->lessThan($start)) {
            $date = $this->dateSuivante($date, $fractionnement);
        }

        while ($date->lessThanOrEqualTo($end)) {
            if (! $lastAppliedAt || $date->greaterThan($lastAppliedAt)) {
                $total += $montant;
            }

            $date = $this->dateSuivante($date, $fractionnement);
        }

        return $total;
    }

    private function dateSuivante(Carbon $date, string $fractionnement): Carbon
    {
        return match ($fractionnement) {
            'mensuel' => $date->copy()->addMonthNoOverflow(),
            'semestriel' => $date->copy()->addMonthsNoOverflow(6),
            'annuel' => $date->copy()->addYearNoOverflow(),
            default => $date->copy()->addCentury(),
        };
    }
}

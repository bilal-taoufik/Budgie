<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PrevisionRequest;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\View\View;

class PrevisionController extends Controller
{
    public function index(): View
    {
        $comptes = auth()->user()->accounts()->get();

        return view('customer.prevision', compact('comptes'));
    }

    public function calculer(PrevisionRequest $request): View
    {
        $datePrevision = Carbon::createFromFormat('Y-m-d', $request->validated('date_prevision'))
            ->endOfMonth();
        $comptes = auth()->user()->accounts()->with('transactions')->get();
        $previsions = [];
        $totalPrevision = 0;

        foreach ($comptes as $compte) {
            $resultat = $this->calculerSoldePrevuCompte($compte, $datePrevision);
            $previsions[] = [
                'account' => $compte,
                'solde' => $resultat['solde'],
                'interets' => $resultat['interets'],
                'taxes' => $resultat['taxes'],
            ];
            $totalPrevision += $resultat['solde'];
        }

        return view('customer.prevision', [
            'previsions' => $previsions,
            'totalPrevision' => round($totalPrevision, 2),
            'selectedDate' => $datePrevision,
        ]);
    }

    private function calculerSoldePrevuCompte(Account $compte, Carbon $datePrevision): array
    {
        $soldeCourant = (float) $compte->solde;
        $totalInterets = 0.0;
        $totalTaxes = 0.0;
        $demain = Carbon::tomorrow()->startOfDay();
        $moisCourant = Carbon::today()->startOfMonth();

        while ($moisCourant->lte($datePrevision)) {
            $debutMois = $moisCourant->copy()->startOfMonth()->max($demain);
            $finMois = $moisCourant->copy()->endOfMonth()->min($datePrevision);

            if ($debutMois->lte($finMois)) {
                foreach ($compte->transactions as $transaction) {
                    $soldeCourant += $transaction->montantTotal($debutMois, $finMois);
                }
            }

            if ($compte->taux_remuneration > 0 && $finMois->isEndOfMonth()) {
                $interetBrut = max(0, $soldeCourant)
                    * ((float) $compte->taux_remuneration / 100 / 12);
                $taxes = $interetBrut * ((float) $compte->taux_imposition / 100);

                $soldeCourant += $interetBrut - $taxes;
                $totalInterets += $interetBrut;
                $totalTaxes += $taxes;
            }

            $moisCourant->addMonthNoOverflow();
        }

        return [
            'solde' => round($soldeCourant, 2),
            'interets' => round($totalInterets, 2),
            'taxes' => round($totalTaxes, 2),
        ];
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrevisionController extends Controller
{
    public function index(): View
    {
        $comptes = Account::all();
        return view('customer.prevision', compact('comptes'));
    }

    public function calculer(Request $request): View
    {
        $request->validate([
            'date_prevision' => 'required|date_format:Y-m-d'
        ]);

        $datePrevision = Carbon::createFromFormat('Y-m-d', $request->date_prevision);
        $comptes = Account::all();

        $previsions = [];
        $totalPrevision = 0;

        // Boucle sur chaque compte
        foreach ($comptes as $compte) {
            $result = $this->calculerSoldePrevuCompte($compte, $datePrevision);
            $previsions[] = [
                'account' => $compte,
                'solde' => $result['solde'],
                'interets' => $result['interets'],
                'taxes' => $result['taxes']
            ];
            $totalPrevision += $result['solde'];
        }

        return view('customer.prevision', [
            'previsions' => $previsions,
            'totalPrevision' => $totalPrevision,
            'selectedDate' => $datePrevision
        ]);
    }

    private function calculerSoldePrevuCompte(Account $compte, Carbon $datePrevision)
    {
        $soldeCourant = $compte->solde;
        $totalInterets = 0;
        $totalTaxes = 0;

        // Récupère toutes les transactions du compte
        $transactionsCompte = $compte->transactions()->get();

        // Boucle mois par mois jusqu'à la date de prévision
        $moisCourant = Carbon::now()->copy()->startOfMonth();

        while ($moisCourant <= $datePrevision) {
            $debutMois = $moisCourant->copy()->startOfMonth();
            $finMois = $moisCourant->copy()->endOfMonth();

            // Appliquer les transactions du mois
            foreach ($transactionsCompte as $transaction) {
                $montantDuMois = $transaction->montantTotal($debutMois, $finMois);
                $soldeCourant = $soldeCourant + $montantDuMois;
            }

            $moisCourant->addMonth();
        }

        // Appliquer les intérêts si le compte a un taux > 0
        if ($compte->taux_remuneration > 0) {
            $interet = $soldeCourant * ($compte->taux_remuneration / 100);
            $impot = $interet * ($compte->taux_imposition / 100);
            $interetNet = $interet - $impot;

            $soldeCourant = $soldeCourant + $interetNet;
            $totalInterets = $interetNet;
            $totalTaxes = $impot;
        }

        return [
            'solde' => $soldeCourant,
            'interets' => $totalInterets,
            'taxes' => $totalTaxes
        ];
    }
}
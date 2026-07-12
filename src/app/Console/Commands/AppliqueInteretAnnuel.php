<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use App\Models\Transaction;

class AppliqueInteretAnnuel extends Command
{
    protected $signature = 'accounts:interet';
    protected $description = 'Applique les intérêts annuels au 31 décembre';

    public function handle(): void
    {
        // recupere les compte ou il y a un taux d'interet superieur a 0
        $comptes = Account::where('taux_remuneration', '>', 0)->get();
        $dateFinAnnee = now()->setMonth(12)->setDay(31);

        foreach ($comptes as $compte) {
            $interet = $compte->solde * ($compte->taux_remuneration / 100);
            $impot = $interet * ($compte->taux_imposition / 100);
            $interetNet = $interet - $impot;

            // Créer une transaction pour l'intérêt
            Transaction::create([
                'account_id' => $compte->id,
                'nom' => 'Intérêts annuels',
                'description' => 'Versement des intérêts au 31/12',
                'montant' => round($interetNet, 2),
                'type' => 'revenu',
                'fractionnement' => 'unique',
                'date_effet' => $dateFinAnnee->format('Y-m-d'),
            ]);

            // Ajouter au solde
            $compte->solde += $interetNet;
            $compte->save();

            $this->info("Intérêts appliqués au compte: {$compte->nom}");
        }
    }
}
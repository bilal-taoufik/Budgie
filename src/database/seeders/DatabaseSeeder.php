<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Depense;
use App\Models\Revenu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'firstname' => 'Bilal',
            'lastname' => 'Admin',
            'email' => 'bll.taoufik@gmail.com',
            'password' => Hash::make('@Budgie2026!!'),
            'role' => 'admin',
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        $customer = User::factory()->create([
            'firstname' => 'Zakaria',
            'lastname' => 'Customer',
            'email' => 'zakaria@gmail.com',
            'password' => Hash::make('@Budgie2026!!'),
            'role' => 'customer',
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        $compteCourant = Account::create([
            'user_id' => $customer->id,
            'name' => 'Compte Courant SG',
            'description' => 'Compte principal pour tester les revenus et depenses recurrentes',
            'solde' => 2750.00,
            'interest_rate' => 0.00,
            'tax_rate' => 0.00,
        ]);

        $epargne = Account::create([
            'user_id' => $customer->id,
            'name' => 'Livret A',
            'description' => 'Compte epargne pour tester plusieurs comptes',
            'solde' => 15987.00,
            'interest_rate' => 0.00,
            'tax_rate' => 0.00,
        ]);

        Revenu::create([
            'account_id' => $compteCourant->id,
            'revenu_nom' => 'Salaire',
            'revenu_description' => 'Revenu mensuel deja credite ce mois-ci',
            'revenu_montant' => 3200.00,
            'revenu_fractionnement' => 'mensuel',
            'revenu_date_effet' => now()->startOfMonth()->toDateString(),
            'last_credited_at' => now()->startOfMonth()->toDateString(),
        ]);

        Revenu::create([
            'account_id' => $epargne->id,
            'revenu_nom' => 'Interets Epargne',
            'revenu_description' => 'Revenu annuel deja credite cette annee',
            'revenu_montant' => 300.00,
            'revenu_fractionnement' => 'annuel',
            'revenu_date_effet' => now()->startOfYear()->toDateString(),
            'last_credited_at' => now()->startOfYear()->toDateString(),
        ]);

        Revenu::create([
            'account_id' => $compteCourant->id,
            'revenu_nom' => 'Prime Exceptionnelle',
            'revenu_description' => 'Revenu ponctuel prevu pour demain',
            'revenu_montant' => 450.00,
            'revenu_fractionnement' => 'unique',
            'revenu_date_effet' => now()->addDay()->toDateString(),
            'last_credited_at' => null,
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Loyer',
            'description' => 'Depense mensuelle deja debitee ce mois-ci',
            'montant' => 950.00,
            'fractionnement' => 'mensuel',
            'date_effet' => now()->startOfMonth()->addDays(4)->toDateString(),
            'last_debited_at' => now()->startOfMonth()->addDays(4)->toDateString(),
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Forfait Mobile',
            'description' => 'Depense mensuelle deja debitee ce mois-ci',
            'montant' => 19.99,
            'fractionnement' => 'mensuel',
            'date_effet' => now()->startOfMonth()->addDays(4)->toDateString(),
            'last_debited_at' => now()->startOfMonth()->addDays(4)->toDateString(),
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Assurance Habitation',
            'description' => 'Depense semestrielle deja debitee',
            'montant' => 180.00,
            'fractionnement' => 'semestriel',
            'date_effet' => now()->startOfYear()->addMonth()->toDateString(),
            'last_debited_at' => now()->startOfYear()->addMonth()->toDateString(),
        ]);

        Depense::create([
            'account_id' => $epargne->id,
            'nom' => 'Achat Ordinateur',
            'description' => 'Depense ponctuelle deja payee',
            'montant' => 1200.00,
            'fractionnement' => 'unique',
            'date_effet' => now()->subDays(10)->toDateString(),
            'last_debited_at' => now()->subDays(10)->toDateString(),
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Abonnement Streaming',
            'description' => 'Depense mensuelle qui sera debitee demain',
            'montant' => 12.99,
            'fractionnement' => 'mensuel',
            'date_effet' => now()->addDay()->toDateString(),
            'last_debited_at' => null,
        ]);
    }
}
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
            'lastname' => 'TAOUFIK',
            'email' => 'bll.taoufik@gmail.com',
            'password' => Hash::make('@Budgie2026!!'),
            'role' => 'admin',
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        $customer = User::factory()->create([
            'firstname' => 'Zakaria',
            'lastname' => 'BOUGUERA',
            'email' => 'zakaria@gmail.com',
            'password' => Hash::make('@Budgie2026!!'),
            'role' => 'customer',
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        $compteCourant = Account::create([
            'user_id' => $customer->id,
            'name' => 'Compte courant',
            'description' => 'Compte principal utilisé pour les dépenses quotidiennes.',
            'solde' => 1850.00,
            'interest_rate' => 0.00,
            'tax_rate' => 0.00,
        ]);

        $livretA = Account::create([
            'user_id' => $customer->id,
            'name' => 'Livret A',
            'description' => 'Épargne réglementée non imposable.',
            'solde' => 12400.00,
            'interest_rate' => 1.50,
            'tax_rate' => 0.00,
        ]);

        $livretFiscalise = Account::create([
            'user_id' => $customer->id,
            'name' => 'Livret fiscalisé',
            'description' => 'Livret bancaire soumis à imposition.',
            'solde' => 6500.00,
            'interest_rate' => 2.00,
            'tax_rate' => 30.00,
        ]);

        Revenu::create([
            'account_id' => $compteCourant->id,
            'revenu_nom' => 'Salaire',
            'revenu_description' => 'Salaire mensuel de janvier 2025 à juin 2026.',
            'revenu_montant' => 1850.00,
            'revenu_fractionnement' => 'mensuel',
            'revenu_date_effet' => '2025-01-01',
            'last_credited_at' => '2026-06-01',
        ]);

        Revenu::create([
            'account_id' => $compteCourant->id,
            'revenu_nom' => 'Prime annuelle',
            'revenu_description' => 'Prime versée en décembre.',
            'revenu_montant' => 600.00,
            'revenu_fractionnement' => 'annuel',
            'revenu_date_effet' => '2025-12-01',
            'last_credited_at' => '2025-12-01',
        ]);

        Revenu::create([
            'account_id' => $livretA->id,
            'revenu_nom' => 'Virement épargne',
            'revenu_description' => 'Versement mensuel vers le Livret A.',
            'revenu_montant' => 150.00,
            'revenu_fractionnement' => 'mensuel',
            'revenu_date_effet' => '2025-01-01',
            'last_credited_at' => '2026-06-01',
        ]);

        Revenu::create([
            'account_id' => $livretFiscalise->id,
            'revenu_nom' => 'Versement livret fiscalisé',
            'revenu_description' => 'Versement régulier sur livret bancaire fiscalisé.',
            'revenu_montant' => 100.00,
            'revenu_fractionnement' => 'mensuel',
            'revenu_date_effet' => '2025-01-01',
            'last_credited_at' => '2026-06-01',
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Loyer',
            'description' => 'Loyer mensuel.',
            'montant' => 780.00,
            'fractionnement' => 'mensuel',
            'date_effet' => '2025-01-05',
            'last_debited_at' => '2026-06-05',
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Électricité',
            'description' => 'Facture électricité mensuelle.',
            'montant' => 85.00,
            'fractionnement' => 'mensuel',
            'date_effet' => '2025-01-10',
            'last_debited_at' => '2026-06-10',
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Forfait mobile',
            'description' => 'Abonnement téléphone.',
            'montant' => 19.99,
            'fractionnement' => 'mensuel',
            'date_effet' => '2025-01-15',
            'last_debited_at' => '2026-06-15',
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Internet',
            'description' => 'Box internet.',
            'montant' => 32.99,
            'fractionnement' => 'mensuel',
            'date_effet' => '2025-01-18',
            'last_debited_at' => '2026-06-18',
        ]);

        Depense::create([
            'account_id' => $compteCourant->id,
            'nom' => 'Courses alimentaires',
            'description' => 'Budget courses mensuel.',
            'montant' => 320.00,
            'fractionnement' => 'mensuel',
            'date_effet' => '2025-01-20',
            'last_debited_at' => '2026-06-20',
        ]);
    }
}
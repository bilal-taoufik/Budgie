<?php

namespace Tests\Feature\Customer;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_client_peut_creer_modifier_et_supprimer_une_depense_et_un_revenu(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $compte = $this->compte($user);

        foreach (['depense', 'revenu'] as $type) {
            $routes = $type === 'depense' ? 'depenses' : 'revenues';
            $this->actingAs($user)->post(route("customer.{$routes}.store"), $this->donnees($compte, $type, ucfirst($type)))
                ->assertRedirect();
            $transaction = $compte->transactions()->where('type', $type)->sole();
            $this->actingAs($user)->put(route("customer.{$routes}.update", $transaction), $this->donnees($compte, $type, "{$type} modifie"))
                ->assertRedirect();
            $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'nom' => ucwords("{$type} modifie")]);
            $this->actingAs($user)->delete(route("customer.{$routes}.delete", $transaction))->assertRedirect();
            $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        }
    }

    public function test_le_filtre_recherche_dans_le_nom_et_la_description_sans_exposer_un_autre_client(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $autre = User::factory()->create(['role' => 'customer']);
        $compte = $this->compte($user);
        $comptePrive = $this->compte($autre);
        $this->transaction($compte, 'Assurance moto', 'Contrat annuel');
        $this->transaction($compte, 'Courses', 'Supermarche');
        $this->transaction($comptePrive, 'Assurance privee', 'Invisible');
        $this->transaction($compte, 'Salaire', 'Entreprise Budgie', 'revenu');
        $this->transaction($compte, 'Prime', 'Exceptionnelle', 'revenu');
        $this->transaction($comptePrive, 'Salaire prive', 'Invisible', 'revenu');

        $this->actingAs($user)->get(route('customer.depenses.index', ['recherche' => 'assurance']))
            ->assertOk()->assertSee('Assurance moto')->assertDontSee('Courses')->assertDontSee('Assurance privee');
        $this->actingAs($user)->get(route('customer.depenses.index', ['recherche' => 'supermarche']))
            ->assertOk()->assertSee('Courses')->assertDontSee('Assurance moto');
    }

    public function test_un_client_ne_peut_pas_modifier_ou_supprimer_une_transaction_d_un_autre(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $autre = User::factory()->create(['role' => 'customer']);
        $comptePrive = $this->compte($autre);
        $transaction = $this->transaction($comptePrive, 'Privee', 'Invisible');
        $compteUser = $this->compte($user);

        $this->actingAs($user)->put(route('customer.depenses.update', $transaction), $this->donnees($compteUser, 'depense', 'Volee'))
            ->assertNotFound();
        $this->actingAs($user)->delete(route('customer.depenses.delete', $transaction))->assertNotFound();
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'nom' => 'Privee']);
    }

    private function compte(User $user): Account
    {
        return Account::create(['user_id' => $user->id, 'nom' => 'Compte', 'solde' => 1000, 'taux_remuneration' => 0, 'taux_imposition' => 0]);
    }

    private function donnees(Account $compte, string $type, string $nom): array
    {
        return ['account_id' => $compte->id, 'type' => $type, 'nom' => $nom, 'description' => 'Description', 'montant' => 50, 'fractionnement' => 'unique', 'date_effet' => now()->addMonth()->format('Y-m-d'), 'date_fin' => null];
    }

    private function transaction(Account $compte, string $nom, string $description, string $type = 'depense'): Transaction
    {
        return Transaction::create(['account_id' => $compte->id, 'nom' => $nom, 'description' => $description, 'montant' => 20, 'type' => $type, 'fractionnement' => 'unique', 'date_effet' => now()->addMonth()]);
    }
}
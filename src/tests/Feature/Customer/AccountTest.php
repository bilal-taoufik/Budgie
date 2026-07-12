<?php

namespace Tests\Feature\Customer;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_client_peut_creer_modifier_et_supprimer_son_compte(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $donnees = $this->donneesCompte('Compte courant');

        $this->actingAs($user)->post(route('customer.accounts.store'), $donnees)
            ->assertRedirect(route('customer.accounts.index'));
        $compte = $user->accounts()->sole();
        $this->assertSame('Compte Courant', $compte->nom);

        $this->actingAs($user)->put(route('customer.accounts.update', $compte), $this->donneesCompte('Livret'))
            ->assertRedirect(route('customer.accounts.index'));
        $this->assertDatabaseHas('accounts', ['id' => $compte->id, 'nom' => 'Livret']);

        $this->actingAs($user)->delete(route('customer.accounts.delete', $compte))
            ->assertRedirect(route('customer.accounts.index'));
        $this->assertDatabaseMissing('accounts', ['id' => $compte->id]);
    }

    public function test_un_client_ne_peut_pas_modifier_ou_supprimer_le_compte_d_un_autre(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $autre = User::factory()->create(['role' => 'customer']);
        $comptePrive = Account::create(['user_id' => $autre->id, ...$this->donneesCompte('Prive')]);

        $this->actingAs($user)->put(route('customer.accounts.update', $comptePrive), $this->donneesCompte('Vole'))
            ->assertNotFound();
        $this->actingAs($user)->delete(route('customer.accounts.delete', $comptePrive))
            ->assertNotFound();
        $this->assertDatabaseHas('accounts', ['id' => $comptePrive->id, 'nom' => 'Prive']);
    }

    public function test_les_routes_client_exigent_une_authentification_et_le_bon_role(): void
    {
        $this->get(route('customer.accounts.index'))->assertRedirect(route('login'));
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('customer.accounts.index'))->assertNotFound();
    }

    private function donneesCompte(string $nom): array
    {
        return ['nom' => $nom, 'description' => 'Description', 'solde' => 100, 'taux_remuneration' => 0, 'taux_imposition' => 0];
    }
}
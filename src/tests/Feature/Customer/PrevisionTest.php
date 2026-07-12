<?php

namespace Tests\Feature\Customer;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrevisionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_prevision_ne_retourne_que_les_comptes_de_l_utilisateur_connecte(): void
    {
        Carbon::setTestNow('2026-01-15 12:00:00');
        $user = User::factory()->create(['role' => 'customer']);
        $autreUtilisateur = User::factory()->create(['role' => 'customer']);
        $compteVisible = Account::create($this->compte($user, 'Compte visible'));
        Account::create($this->compte($autreUtilisateur, 'Compte prive'));

        $response = $this->actingAs($user)->get(route('customer.previsions.calculer', [
            'date_prevision' => '2026-02-01',
        ]));

        $response->assertOk();
        $response->assertViewHas('previsions', function (array $previsions) use ($compteVisible) {
            return count($previsions) === 1
                && $previsions[0]['account']->is($compteVisible);
        });
        $response->assertDontSee('Compte prive');
    }

    public function test_prevision_applique_les_echeances_futures_et_les_interets_nets_mensuels(): void
    {
        Carbon::setTestNow('2026-01-15 12:00:00');
        $user = User::factory()->create(['role' => 'customer']);
        $compte = Account::create([
            ...$this->compte($user, 'Livret'),
            'solde' => 1200,
            'taux_remuneration' => 12,
            'taux_imposition' => 30,
        ]);
        Transaction::create([
            'account_id' => $compte->id,
            'nom' => 'Versement',
            'montant' => 120,
            'type' => 'revenu',
            'fractionnement' => 'mensuel',
            'date_effet' => '2026-01-20',
        ]);

        $response = $this->actingAs($user)->get(route('customer.previsions.calculer', [
            'date_prevision' => '2026-02-01',
        ]));

        $response->assertOk();
        $response->assertViewHas('previsions', function (array $previsions) {
            return $previsions[0]['solde'] === 1459.38
                && $previsions[0]['interets'] === 27.69
                && $previsions[0]['taxes'] === 8.31;
        });
        $response->assertViewHas('selectedDate', fn (Carbon $date) => $date->isSameDay('2026-02-28'));
    }

    private function compte(User $user, string $nom): array
    {
        return [
            'user_id' => $user->id,
            'nom' => $nom,
            'description' => null,
            'solde' => 100,
            'taux_remuneration' => 0,
            'taux_imposition' => 0,
        ];
    }
}

<?php

namespace Tests\Feature\Customer;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_dashboard_totals_only_include_authenticated_customers_data(): void
    {
        Carbon::setTestNow('2026-07-18 12:00:00');
        $user = User::factory()->create(['role' => 'customer']);
        $other = User::factory()->create(['role' => 'customer']);
        $account = Account::create(['user_id' => $user->id, 'nom' => 'Visible', 'solde' => 1250]);
        $private = Account::create(['user_id' => $other->id, 'nom' => 'Prive', 'solde' => 9999]);
        $this->transaction($account, 'Salaire', 'revenu', 500);
        $this->transaction($account, 'Courses', 'depense', 120);
        $this->transaction($private, 'Secret', 'revenu', 9999);

        $response = $this->actingAs($user)->get(route('customer.dashboard'));

        $response->assertOk()
            ->assertViewHas('soldeTotal', 1250)
            ->assertViewHas('revenuTotal', 500.0)
            ->assertViewHas('depenseTotal', 120.0)
            ->assertSee('Visible')
            ->assertDontSee('Prive')
            ->assertDontSee('Secret');
    }

    public function test_empty_dashboard_renders_without_error(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get(route('customer.dashboard'))
            ->assertOk()
            ->assertViewHas('soldeTotal', 0);
    }

    private function transaction(Account $account, string $name, string $type, float $amount): Transaction
    {
        return Transaction::create([
            'account_id' => $account->id,
            'nom' => $name,
            'montant' => $amount,
            'type' => $type,
            'fractionnement' => 'unique',
            'date_effet' => '2026-07-10',
        ]);
    }
}

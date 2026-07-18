<?php

namespace Tests\Feature\Customer;

use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionBalanceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_create_update_and_delete_transaction_adjust_account_balance(): void
    {
        Carbon::setTestNow('2026-07-18 12:00:00');
        $user = User::factory()->create(['role' => 'customer']);
        $account = Account::create(['user_id' => $user->id, 'nom' => 'Compte', 'solde' => 1000]);

        $this->actingAs($user)->post(route('customer.revenues.store'), $this->data($account, 'revenu', 100))
            ->assertSessionHasNoErrors();
        $transaction = $account->transactions()->sole();
        $this->assertEquals(1100, $account->fresh()->solde);
        $this->assertTrue($transaction->fresh()->derniere_application->isSameDay('2026-07-18'));

        $this->actingAs($user)->put(route('customer.depenses.update', $transaction), $this->data($account, 'depense', 40))
            ->assertSessionHasNoErrors();
        $this->assertEquals(960, $account->fresh()->solde);

        $this->actingAs($user)->delete(route('customer.depenses.delete', $transaction))
            ->assertSessionHasNoErrors();
        $this->assertEquals(1000, $account->fresh()->solde);
    }

    public function test_future_transaction_does_not_change_current_balance(): void
    {
        Carbon::setTestNow('2026-07-18 12:00:00');
        $user = User::factory()->create(['role' => 'customer']);
        $account = Account::create(['user_id' => $user->id, 'nom' => 'Compte', 'solde' => 1000]);
        $data = $this->data($account, 'depense', 250);
        $data['date_effet'] = '2026-08-01';

        $this->actingAs($user)->post(route('customer.depenses.store'), $data)
            ->assertSessionHasNoErrors();

        $this->assertEquals(1000, $account->fresh()->solde);
    }

    public function test_transaction_cannot_be_attached_to_another_users_account(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $other = User::factory()->create(['role' => 'customer']);
        $privateAccount = Account::create(['user_id' => $other->id, 'nom' => 'Prive', 'solde' => 1000]);

        $this->actingAs($user)->post(route('customer.depenses.store'), $this->data($privateAccount, 'depense', 10))
            ->assertSessionHasErrors('account_id');

        $this->assertDatabaseCount('transactions', 0);
        $this->assertEquals(1000, $privateAccount->fresh()->solde);
    }

    private function data(Account $account, string $type, float $amount): array
    {
        return [
            'account_id' => $account->id,
            'nom' => 'Operation',
            'description' => null,
            'montant' => $amount,
            'type' => $type,
            'fractionnement' => 'unique',
            'date_effet' => '2026-07-18',
            'date_fin' => null,
        ];
    }
}

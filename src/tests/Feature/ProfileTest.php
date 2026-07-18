<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_and_update_profile(): void
    {
        $user = User::factory()->create(['role' => 'customer', 'email' => 'old@example.com']);

        $this->actingAs($user)->get(route('customer.profile.index'))->assertOk();
        $this->actingAs($user)->put(route('customer.profile.info'), [
            'firstname' => '  jean ',
            'lastname' => ' dupont ',
            'email' => ' NEW@EXAMPLE.COM ',
        ])->assertSessionHasNoErrors()->assertRedirect(route('customer.profile.index'));

        $user->refresh();
        $this->assertSame('Jean', $user->firstname);
        $this->assertSame('DUPONT', $user->lastname);
        $this->assertSame('new@example.com', $user->email);
    }

    public function test_profile_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'used@example.com']);
        $user = User::factory()->create(['role' => 'customer', 'email' => 'mine@example.com']);

        $this->actingAs($user)->put(route('customer.profile.info'), [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => 'used@example.com',
        ])->assertSessionHasErrors('email');

        $this->assertSame('mine@example.com', $user->fresh()->email);
    }

    public function test_customer_account_deletion_requires_password_and_cascades_data(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => bcrypt('Password123!'),
        ]);
        $account = $user->accounts()->create(['nom' => 'Compte', 'solde' => 10]);
        $transaction = $account->transactions()->create([
            'nom' => 'Salaire', 'montant' => 10, 'type' => 'revenu',
            'fractionnement' => 'unique', 'date_effet' => now(),
        ]);

        $this->actingAs($user)->delete(route('customer.profile.delete'), ['password' => 'WrongPassword1!'])
            ->assertSessionHasErrors('password');
        $this->assertNotNull($user->fresh());

        $this->actingAs($user)->delete(route('customer.profile.delete'), ['password' => 'Password123!'])
            ->assertRedirect(route('home'));

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_roles_cannot_access_each_others_profile(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($customer)->get(route('admin.profile.index'))->assertNotFound();
        $this->actingAs($admin)->get(route('customer.profile.index'))->assertNotFound();
    }
}

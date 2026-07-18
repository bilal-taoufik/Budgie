<?php

namespace Tests\Feature\Admin;

use App\Mail\VerifyMail;
use App\Mail\WelcomeMail;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_global_statistics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        Account::create(['user_id' => $customer->id, 'nom' => 'Compte', 'solde' => 100]);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('totalUsers', 2)
            ->assertViewHas('totalCustomers', 1)
            ->assertViewHas('totalAdmins', 1)
            ->assertViewHas('totalAccounts', 1);
    }

    public function test_customer_and_guest_cannot_access_admin_routes(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->actingAs($customer)->get(route('admin.users.index'))->assertNotFound();
    }

    public function test_admin_can_create_another_admin(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'firstname' => '  alice ',
            'lastname' => ' martin ',
            'email' => ' ADMIN@EXAMPLE.COM ',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.users.index'));

        $created = User::where('email', 'admin@example.com')->sole();
        $this->assertSame('admin', $created->role);
        $this->assertFalse((bool) $created->email_verified);
        Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->hasTo($created->email));
        Mail::assertSent(VerifyMail::class, fn ($mail) => $mail->hasTo($created->email));
    }

    public function test_admin_can_delete_another_user_but_not_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($admin)->delete(route('admin.users.delete', $admin))
            ->assertRedirect(route('admin.users.index'))->assertSessionHas('error');
        $this->assertNotNull($admin->fresh());

        $this->actingAs($admin)->delete(route('admin.users.delete', $customer))
            ->assertRedirect(route('admin.users.index'))->assertSessionHas('success');
        $this->assertNull($customer->fresh());
    }

    public function test_last_admin_cannot_delete_profile_but_can_when_another_admin_exists(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('Password123!'),
        ]);

        $this->actingAs($admin)->delete(route('admin.profile.delete'), ['password' => 'Password123!'])
            ->assertRedirect(route('admin.profile.index'))->assertSessionHas('error');
        $this->assertNotNull($admin->fresh());

        User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->delete(route('admin.profile.delete'), ['password' => 'Password123!'])
            ->assertRedirect(route('home'))->assertSessionHas('success');
        $this->assertNull($admin->fresh());
        $this->assertGuest();
    }

    public function test_admin_can_update_profile_information(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'old-admin@example.com']);

        $this->actingAs($admin)->put(route('admin.profile.info'), [
            'firstname' => '  admin ',
            'lastname' => ' principal ',
            'email' => ' NEW-ADMIN@EXAMPLE.COM ',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.profile.index'));

        $admin->refresh();
        $this->assertSame('Admin', $admin->firstname);
        $this->assertSame('PRINCIPAL', $admin->lastname);
        $this->assertSame('new-admin@example.com', $admin->email);
    }
}

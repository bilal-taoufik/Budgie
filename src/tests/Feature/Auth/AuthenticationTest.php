<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get(route('login'))->assertOk();
    }

    public function test_verified_customer_can_authenticate(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified' => true,
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->post(route('login'), [
            'email' => strtoupper($user->email),
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_verified_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified' => true,
            'password' => bcrypt('Password123!'),
        ]);

        $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_unverified_user_cannot_authenticate_and_can_request_a_new_email(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified' => false,
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $response->assertSessionHas('btn_resend', true);
        $response->assertSessionHas('email', $user->email);
    }

    public function test_invalid_password_is_rejected(): void
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'password' => bcrypt('Password123!'),
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'WrongPassword1!',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }
}

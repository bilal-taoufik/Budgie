<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_must_confirm_current_password_before_changing_it(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => bcrypt('CurrentPassword1!'),
        ]);

        $this->actingAs($user)->put(route('customer.profile.password'), [
            'current_password' => 'WrongPassword1!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('CurrentPassword1!', $user->fresh()->password));
    }

    public function test_customer_can_change_password_with_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => bcrypt('CurrentPassword1!'),
        ]);

        $this->actingAs($user)->put(route('customer.profile.password'), [
            'current_password' => 'CurrentPassword1!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertSessionHasNoErrors()->assertRedirect(route('customer.profile.index'));

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }
}

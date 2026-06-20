<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('@Budgie2026!!'),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('@Budgie2026!!'),
            'role' => 'customer',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'firstname' => 'Bilal',
            'lastname' => 'TAOUFIK',
            'email' => 'bll.taoufik@gmail.com',
            'password' => Hash::make('@Budgie2026!!'),
            'role' => 'admin',
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        $customer = User::create([
            'firstname' => 'Zakaria',
            'lastname' => 'BOUGUERA',
            'email' => 'zakaria@gmail.com',
            'password' => Hash::make('@Budgie2026!!'),
            'role' => 'customer',
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@pos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create manager user
        \App\Models\User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@pos.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        // Create cashier user
        \App\Models\User::factory()->create([
            'name' => 'Cashier User',
            'email' => 'cashier@pos.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

    }
}

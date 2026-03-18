<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Default Admin User for testing (Member 1's Role)
        User::updateOrCreate(
            ['email' => 'admin@mela.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0123456789',
            ]
        );

        // 2. Create other roles for Members 2 and 3 to test later
        User::updateOrCreate(
            ['email' => 'vendor@mela.com'],
            ['name' => 'Demo Vendor', 'password' => Hash::make('password123'), 'role' => 'vendor']
        );

        User::updateOrCreate(
            ['email' => 'visitor@mela.com'],
            ['name' => 'Demo Visitor', 'password' => Hash::make('password123'), 'role' => 'visitor']
        );

        User::updateOrCreate(
            ['email' => 'employee@mela.com'],
            ['name' => 'Demo Employee', 'password' => Hash::make('password123'), 'role' => 'employee']
        );
    }
}

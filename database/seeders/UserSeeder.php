<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mela.com',
            'password' => $password,
            'role' => 'admin',
            'phone' => '1234567890',
        ]);

        User::create([
            'name' => 'Vendor User',
            'email' => 'vendor@mela.com',
            'password' => $password,
            'role' => 'vendor',
            'phone' => '0987654321',
        ]);

        User::create([
            'name' => 'Employee User',
            'email' => 'employee@mela.com',
            'password' => $password,
            'role' => 'employee',
            'phone' => '1112223333',
        ]);

        User::create([
            'name' => 'Visitor User',
            'email' => 'visitor@mela.com',
            'password' => $password,
            'role' => 'visitor',
            'phone' => '4445556666',
        ]);
    }
}

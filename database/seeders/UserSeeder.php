<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make("password"),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@gmail.com',
            'password' => Hash::make("password"),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => Hash::make("password"),
            'role' => 'user',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds the two demo users required by specifications:
     * 1. Admin: admin@jajal.com / password
     * 2. Viewer: viewer@jajal.com / password
     */
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@jajal.com'],
            [
                'name' => 'Dr. Eleanor Reyes (Admin)',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Viewer User
        User::firstOrCreate(
            ['email' => 'viewer@jajal.com'],
            [
                'name' => 'Nurse Samira Khan (Viewer)',
                'password' => Hash::make('password'),
                'role' => 'viewer',
                'email_verified_at' => now(),
            ]
        );
    }
}

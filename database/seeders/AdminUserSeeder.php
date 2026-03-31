<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@tryon.com'],
            [
                'name'              => 'Admin',
                'email'             => 'admin@tryon.com',
                'password'          => Hash::make('admin123!'),
                'is_admin'          => true,
                'is_active'         => true,
                'credit_balance'    => 999,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );
    }
}
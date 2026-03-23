<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminAndDirectorSeeder extends Seeder
{
    /**
     * Seed admin and director accounts.
     */
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['email' => 'xxx@xxx.com'],
            [
                'name' => 'xxx',
                'password' => 'xxx!',
                'role' => UserRole::Admin,
                'division_id' => null,
                'is_active' => true,
            ],
        );

        // Director account
        User::updateOrCreate(
            ['email' => 'xxx@xxx.com'],
            [
                'name' => 'xxx',
                'password' => 'xxx!',
                'role' => UserRole::Director,
                'division_id' => null,
                'is_active' => true,
            ],
        );
    }
}

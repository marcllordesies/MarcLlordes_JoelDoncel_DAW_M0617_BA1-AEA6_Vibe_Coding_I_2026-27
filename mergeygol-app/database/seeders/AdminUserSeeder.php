<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mergeygol.local'],
            [
                'name' => 'Admin Local',
                'password' => Hash::make('Admin12345!'),
                'is_admin' => true,
            ]
        );
    }
}

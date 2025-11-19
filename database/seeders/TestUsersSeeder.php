<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {

        $user = User::firstOrCreate(
            ['email' => 'user@housing.local'],
            [
                'first_name' => 'Simple',
                'second_name' => 'User',
                'password' => Hash::make('password'),
                'status_account' => 'active',
            ]
        );

        $companyHead = User::firstOrCreate(
            ['email' => 'head@housing.local'],
            [
                'first_name' => 'Company',
                'second_name' => 'Head',
                'password' => Hash::make('password'),
                'status_account' => 'active',
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@housingtest.local'],
            [
                'first_name' => 'Company',
                'second_name' => 'Admin',
                'password' => Hash::make('password'),
                'status_account' => 'active',
            ]
        );

    }
}

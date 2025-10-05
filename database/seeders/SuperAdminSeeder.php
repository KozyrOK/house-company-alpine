<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('seeder.superadmin_email');
        $password = config('seeder.superadmin_password');

        User::firstOrCreate(
            ['email' => $email],
            [
                'first_name' => 'Super',
                'second_name' => 'Admin',
                'email' => 'admin@housing.local',
                'password' => Hash::make('password'),
                'status_account' => 'active',
                'remember_token' => Str::random(10),
            ]
        );
    }
}

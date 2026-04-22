<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.superadmin_email', 'superadmin@housing.local');
        $password = config('app.superadmin_password', 'password');

        $avatarPath = base_path('resources/images/default_avatar.webp');

        $superAdmin = User::updateOrCreate(
            ['email' => $email],
            [
                'first_name' => 'Super',
                'second_name' => 'Admin',
                'password' => Hash::make($password),
                'status_account' => 'active',
                'remember_token' => Str::random(10),
                'avatar_path' => $avatarPath,
            ]
        );

        $logoPath = base_path('resources/images/default-image-company.webp');

        $systemCompany = Company::updateOrCreate(
            ['name' => 'Housing Platform Core'],
            [
                'address' => 'System address',
                'city' => 'System city',
                'description' => 'Technical company stub for superadmin role.',
                'logo_path' => $logoPath,
            ]
        );

        $superAdmin->companies()->syncWithoutDetaching([
            $systemCompany->id => ['role' => 'superadmin'],
        ]);
    }
}

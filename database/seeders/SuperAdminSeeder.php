<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.superadmin_email');
        $password = config('app.superadmin_password');

        $superAdmin = User::firstOrCreate(
        ['email' => $email],
            [
                'first_name' => 'Super',
                'second_name' => 'Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'status_account' => 'active',
                'remember_token' => Str::random(10),
            ]
        );

        $company = Company::firstOrCreate(
            ['name' => 'Housing Platform Core'],
            [
                'address' => 'HQ address',
                'city' => 'Paris',
                'description' => 'Technical company for global superadmin role assignment.',
            ]
        );

        $superAdmin->companies()->syncWithoutDetaching([
            $company->id => ['role' => 'superadmin'],
        ]);

    }
}

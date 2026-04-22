<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $logoFiles = [
            base_path('resources/images/company-logo/Company-logo1.webp'),
            base_path('resources/images/company-logo/Company-logo2.webp'),
            base_path('resources/images/company-logo/Company-logo3.webp'),
            base_path('resources/images/company-logo/Company-logo4.webp'),
        ];
        shuffle($logoFiles);

        $companiesData = [
            ['name' => 'Green Residence', 'address' => '12 Oak Street', 'city' => 'Kyiv', 'description' => 'Residential company 1'],
            ['name' => 'Sunset House', 'address' => '25 River Road', 'city' => 'Lviv', 'description' => 'Residential company 2'],
            ['name' => 'Central Plaza', 'address' => '8 Freedom Ave', 'city' => 'Odesa', 'description' => 'Residential company 3'],
            ['name' => 'Park View', 'address' => '41 Garden Lane', 'city' => 'Dnipro', 'description' => 'Residential company 4'],
        ];

        $companies = [];
        foreach ($companiesData as $index => $data) {
            $logoPath = null;
            $logoSource = $logoFiles[$index] ?? null;

            if ($logoSource && file_exists($logoSource)) {
                $logoPath = 'company_logos/seed_company_' . ($index + 1) . '_' . basename($logoSource);
                Storage::disk('public')->put($logoPath, file_get_contents($logoSource));
            }

            $companies[] = Company::updateOrCreate(
                ['name' => $data['name']],
                [...$data, 'logo_path' => $logoPath]
            );
        }

        $avatarFiles = [
            base_path('resources/images/avatar_users/avatar_1.webp'),
            base_path('resources/images/avatar_users/avatar_2.webp'),
            base_path('resources/images/avatar_users/avatar_3.webp'),
            base_path('resources/images/avatar_users/avatar_4.webp'),
            base_path('resources/images/avatar_users/avatar_5.webp'),
            base_path('resources/images/avatar_users/avatar_6.webp'),
        ];

        $usersData = [];

        foreach ($companies as $idx => $company) {
            $n = $idx + 1;
            $usersData[] = ['email' => "company{$n}.admin1@housing.local", 'first_name' => "Admin{$n}", 'second_name' => 'One', 'roles' => [$company->id => 'admin']];
            $usersData[] = ['email' => "company{$n}.admin2@housing.local", 'first_name' => "Admin{$n}", 'second_name' => 'Two', 'roles' => [$company->id => 'admin']];
            $usersData[] = ['email' => "company{$n}.head1@housing.local", 'first_name' => "Head{$n}", 'second_name' => 'One', 'roles' => [$company->id => 'company_head']];
            $usersData[] = ['email' => "company{$n}.head2@housing.local", 'first_name' => "Head{$n}", 'second_name' => 'Two', 'roles' => [$company->id => 'company_head']];
            $usersData[] = ['email' => "company{$n}.user1@housing.local", 'first_name' => "User{$n}", 'second_name' => 'One', 'roles' => [$company->id => 'user']];
            $usersData[] = ['email' => "company{$n}.user2@housing.local", 'first_name' => "User{$n}", 'second_name' => 'Two', 'roles' => [$company->id => 'user']];
        }

        $usersData[] = ['email' => 'multi.role1@housing.local', 'first_name' => 'Multi', 'second_name' => 'RoleOne', 'roles' => [$companies[0]->id => 'admin', $companies[1]->id => 'user']];
        $usersData[] = ['email' => 'multi.role2@housing.local', 'first_name' => 'Multi', 'second_name' => 'RoleTwo', 'roles' => [$companies[1]->id => 'admin', $companies[2]->id => 'user']];
        $usersData[] = ['email' => 'multi.role3@housing.local', 'first_name' => 'Multi', 'second_name' => 'RoleThree', 'roles' => [$companies[2]->id => 'admin', $companies[3]->id => 'user']];
        $usersData[] = ['email' => 'multi.role4@housing.local', 'first_name' => 'Multi', 'second_name' => 'RoleFour', 'roles' => [$companies[3]->id => 'admin', $companies[0]->id => 'user']];

        foreach ($usersData as $index => $data) {
            $avatarPath = null;
            $avatarSource = $avatarFiles[array_rand($avatarFiles)] ?? null;

            if ($avatarSource && file_exists($avatarSource)) {
                $avatarPath = 'avatars/seed_user_' . ($index + 1) . '_' . basename($avatarSource);
                Storage::disk('public')->put($avatarPath, file_get_contents($avatarSource));
            }

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'second_name' => $data['second_name'],
                    'password' => $password,
                    'status_account' => 'active',
                    'avatar_path' => $avatarPath,
                ]
            );

            $syncRoles = [];
            foreach ($data['roles'] as $companyId => $role) {
                $syncRoles[$companyId] = ['role' => $role];
            }

            $user->companies()->sync($syncRoles);
        }
    }
}

/*
Test credentials

Password for all users below: password

Superadmin:
superadmin@housing.local

Company users:
company1.admin1@housing.local
company1.admin2@housing.local
company1.head1@housing.local
company1.head2@housing.local
company1.user1@housing.local
company1.user2@housing.local
company2.admin1@housing.local
company2.admin2@housing.local
company2.head1@housing.local
company2.head2@housing.local
company2.user1@housing.local
company2.user2@housing.local
company3.admin1@housing.local
company3.admin2@housing.local
company3.head1@housing.local
company3.head2@housing.local
company3.user1@housing.local
company3.user2@housing.local
company4.admin1@housing.local
company4.admin2@housing.local
company4.head1@housing.local
company4.head2@housing.local
company4.user1@housing.local
company4.user2@housing.local

Cross-company users (4):
| - multi.role1@housing.local
| - multi.role2@housing.local
| - multi.role3@housing.local
| - multi.role4@housing.local
*/

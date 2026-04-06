<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $companies = collect([
            Company::updateOrCreate(
                ['name' => 'Green Residence'],
                [
                    'address' => '12 Oak Street',
                    'city' => 'Kyiv',
                    'description' => 'Residential company 1',
                ]
            ),
            Company::updateOrCreate(
                ['name' => 'Sunset House'],
                [
                    'address' => '25 River Road',
                    'city' => 'Lviv',
                    'description' => 'Residential company 2',
                ]
            ),
            Company::updateOrCreate(
                ['name' => 'Central Plaza'],
                [
                    'address' => '8 Freedom Ave',
                    'city' => 'Odesa',
                    'description' => 'Residential company 3',
                ]
            ),
            Company::updateOrCreate(
                ['name' => 'Park View'],
                [
                    'address' => '41 Garden Lane',
                    'city' => 'Dnipro',
                    'description' => 'Residential company 4',
                ]
            ),
        ]);

        $admin1 = User::updateOrCreate(
            ['email' => 'admin1@housing.local'],
            [
                'first_name' => 'Admin',
                'second_name' => 'One',
                'password' => $password,
                'status_account' => 'active',
            ]
        );

        $admin2 = User::updateOrCreate(
            ['email' => 'admin2@housing.local'],
            [
                'first_name' => 'Admin',
                'second_name' => 'Two',
                'password' => $password,
                'status_account' => 'active',
            ]
        );

        $head1 = User::updateOrCreate(
            ['email' => 'head1@housing.local'],
            [
                'first_name' => 'Head',
                'second_name' => 'One',
                'password' => $password,
                'status_account' => 'active',
            ]
        );

        $head2 = User::updateOrCreate(
            ['email' => 'head2@housing.local'],
            [
                'first_name' => 'Head',
                'second_name' => 'Two',
                'password' => $password,
                'status_account' => 'active',
            ]
        );

        $head3 = User::updateOrCreate(
            ['email' => 'head3@housing.local'],
            [
                'first_name' => 'Head',
                'second_name' => 'Three',
                'password' => $password,
                'status_account' => 'active',
            ]
        );

        $head4 = User::updateOrCreate(
            ['email' => 'head4@housing.local'],
            [
                'first_name' => 'Head',
                'second_name' => 'Four',
                'password' => $password,
                'status_account' => 'active',
            ]
        );

        $users = collect();

        for ($i = 1; $i <= 10; $i++) {
            $users->push(
                User::updateOrCreate(
                    ['email' => "user{$i}@housing.local"],
                    [
                        'first_name' => 'User',
                        'second_name' => (string) $i,
                        'password' => $password,
                        'status_account' => 'active',
                    ]
                )
            );
        }

        $admin1->companies()->sync([
            $companies[0]->id => ['role' => 'admin'],
            $companies[1]->id => ['role' => 'admin'],
        ]);

        $admin2->companies()->sync([
            $companies[2]->id => ['role' => 'admin'],
            $companies[3]->id => ['role' => 'admin'],
        ]);

        $head1->companies()->sync([
            $companies[0]->id => ['role' => 'company_head'],
        ]);

        $head2->companies()->sync([
            $companies[1]->id => ['role' => 'company_head'],
        ]);

        $head3->companies()->sync([
            $companies[2]->id => ['role' => 'company_head'],
        ]);

        $head4->companies()->sync([
            $companies[3]->id => ['role' => 'company_head'],
        ]);

        $distribution = [
            1 => [0],
            2 => [1],
            3 => [2],
            4 => [3],
            5 => [0, 1],
            6 => [1, 2],
            7 => [2, 3],
            8 => [0, 3],
            9 => [0],
            10 => [2],
        ];

        foreach ($users as $index => $user) {
            $userNumber = $index + 1;
            $attachData = [];

            foreach ($distribution[$userNumber] as $companyIndex) {
                $attachData[$companies[$companyIndex]->id] = ['role' => 'user'];
            }

            $user->companies()->sync($attachData);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Test credentials
|--------------------------------------------------------------------------
|
| All test users have password: password
|
| Superadmin:
| - superadmin@housing.local / password
|
| Admins:
| - admin1@housing.local / password
| - admin2@housing.local / password
|
| Company heads:
| - head1@housing.local / password
| - head2@housing.local / password
| - head3@housing.local / password
| - head4@housing.local / password
|
| Regular users:
| - user1@housing.local / password
| - user2@housing.local / password
| - user3@housing.local / password
| - user4@housing.local / password
| - user5@housing.local / password
| - user6@housing.local / password
| - user7@housing.local / password
| - user8@housing.local / password
| - user9@housing.local / password
| - user10@housing.local / password
|
*/

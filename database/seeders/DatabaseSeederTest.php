<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class DatabaseSeederTest extends Seeder
{
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            TestUsersSeeder::class,
        ]);

        $superadmin = User::where('email', 'admin@housing.local')->first();

        $companies = Company::factory(2)->create();

        $users = User::whereIn('email', [
            'user@housing.local',
            'head@housing.local',
            'admin@housingtest.local',
        ])->get();

        $companies->each(function ($company) use ($superadmin, $users) {

            foreach ($users as $user) {

                $role =
                    $user->email === 'admin@housingtest.local' ? 'admin' :
                        ($user->email === 'head@housing.local' ? 'company_head' : 'user');

                $user->companies()->syncWithoutDetaching([
                    $company->id => ['role' => $role]
                ]);
            }

            if ($superadmin) {
                $superadmin->companies()->syncWithoutDetaching([
                    $company->id => ['role' => 'superadmin']
                ]);
            }
        });

        $this->call(PostSeeder::class);
    }

}

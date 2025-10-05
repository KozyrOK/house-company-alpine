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
        ]);

        $superadmin = User::where('email', 'admin@housing.local')->first();

        $companies = Company::factory(2)->create();

        $companies->each(function ($company) use ($superadmin) {
            $users = User::factory(3)->create();

            foreach ($users as $i => $user) {
                $role = $i === 0 ? 'company_head' : 'user';
                $user->companies()->attach($company->id, ['role' => $role]);
            }

            if ($superadmin) {
                $superadmin->companies()->syncWithoutDetaching([
                    $company->id => ['role' => 'superadmin']
                ]);
            }
        });
    }
}

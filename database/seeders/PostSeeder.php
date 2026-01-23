<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        User::with('companies')->each(function (User $user) {

            if ($user->isSuperAdmin()) {
                return;
            }

            $company = $user->companies->first();
            if (!$company) {
                return;
            }

            Post::factory()
                ->count(2)
                ->create([
                    'company_id' => $company->id,
                    'user_id'    => $user->id,
                    'status'     => 'publish',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        User::with('companies')->get()->each(function (User $user) {
            if ($user->isSuperAdmin()) {
                return;
            }

            $company = $user->companies->first();

            if (!$company) {
                return;
            }

            Post::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'company_id' => $company->id,
                    'title' => 'Test post by ' . $user->first_name . ' ' . $user->second_name,
                    'content' => 'This is a seeded test post for ' . $user->email,
                    'status' => 'publish',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]
            );
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::with('companies')->get();

        foreach ($users as $user) {
            if ($user->isSuperAdmin()) {
            continue;
        }

        $company = $user->companies->first();
            if (!$company) {
                continue;
            }

            for ($i = 1; $i <= 2; $i++) {
                Post::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'company_id' => $company->id,
                        'title' => "Seeded post {$i} by {$user->email}",
                    ],
                    [
                        'content' => "Seeded post {$i} content for {$user->email}",
                        'status' => 'publish',
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]
                );
            }
        }
    }
}

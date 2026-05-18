<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::with(['companies' => fn ($q) => $q->withPivot('role', 'status_membership')])->get();

        foreach ($users as $user) {
            if ($user->isSuperAdmin() || $user->status_account !== 'active') {
                continue;
            }

            $membership = $user->companies->firstWhere('pivot.status_membership', 'active');
            if (!$membership) {
                continue;
            }

            $postStatus = $membership->pivot->role === 'admin' ? 'publish' : 'pending';

            for ($i = 1; $i <= 2; $i++) {
                Post::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'company_id' => $membership->id,
                        'title' => "Seeded post {$i} by {$user->email}",
                    ],
                    [
                        'content' => "Seeded post {$i} content for {$user->email}",
                        'status' => $postStatus,
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]
                );
            }
        }
    }
}

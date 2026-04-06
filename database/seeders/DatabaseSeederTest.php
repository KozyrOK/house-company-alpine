<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeederTest extends Seeder
{
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            TestUsersSeeder::class,
            PostSeeder::class,
        ]);
    }
}

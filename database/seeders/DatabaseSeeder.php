<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'SuperAdmin'
            ],
            [
                'name' => 'Sales',
                'email' => 'sales@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'Sales'
            ],
            [
                'name' => 'Purchase',
                'email' => 'purchase@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'Purchase'
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'Manager'
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

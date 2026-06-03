<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@padang.go.id',
                'role' => 'super_admin',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@padang.go.id',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Operator User',
                'email' => 'operator@padang.go.id',
                'role' => 'operator',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Kontributor User',
                'email' => 'kontributor@padang.go.id',
                'role' => 'kontributor',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $data) {
            // Update or create to avoid duplicate seeding
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => $data['role'],
                    'password' => $data['password'],
                ]
            );
        }
    }
}

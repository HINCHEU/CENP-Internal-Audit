<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create departments (idempotent)
        $departments = [
            ['name' => 'AFD', 'description' => 'Admin and Finance Department', 'status' => 'active'],
            ['name' => 'BDD', 'description' => 'Business Development Department', 'status' => 'active'],
            ['name' => 'OPD', 'description' => 'Operations Department', 'status' => 'active'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }

        // Create admin user (idempotent), no department
        User::firstOrCreate(
            ['email' => 'admin@cenp.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'user_code' => 'USR-0001',
                'department_id' => null,
                'status' => 'active'
            ]
        );
    }
}
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
            ['name' => 'AFD', 'description' => '', 'status' => 'active'],
            ['name' => 'BBD', 'description' => '', 'status' => 'active'],
            ['name' => 'OPD', 'description' => '', 'status' => 'active'],
            ['name' => 'IT Infrastructure', 'description' => 'Manages all internal IT systems and networks.', 'status' => 'active'],
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
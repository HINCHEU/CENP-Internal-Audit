<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $dept = \App\Models\Department::create([
            'name' => 'IT Infrastructure',
            'description' => 'Manages all internal IT systems and networks.',
            'status' => 'active'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@cenp.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'user_code' => 'USR-0001',
            'department_id' => $dept->id,
            'status' => 'active'
        ]);
    }
}

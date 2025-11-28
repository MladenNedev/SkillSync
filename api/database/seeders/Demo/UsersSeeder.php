<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->create([
            'name'      => 'Demo User',
            'username'  => 'demo_user',
            'email'     => 'demo@skillsync.test',
        ]);
        
        User::factory()->create([
            'name'     => 'John Doe',
            'username' => 'john_doe',
            'email'    => 'john@example.com',
        ]);

        User::factory()->create([
            'name'     => 'Sarah Smith',
            'username' => 'sarah_smith',
            'email'    => 'sarah@example.com',
        ]);
    }
}
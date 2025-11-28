<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use App\Models\Challenge;

class ChallengesSeeder extends Seeder 
{
    public function run(): void
    {

        $challenges = [
            [
                'title' => '30 Days of Algorithms',
                'description' => 'Solve one algorithm or data structure problem every day.',
            ],
            [
                'title' => 'Daily System Design Prompt',
                'description' => 'Sketch one high-level system design solution per day.',
            ],
            [
                'title' => 'Laravel API Sprint',
                'description' => 'Implement or refactor one backend API endpoint each day.',
            ],
            [
                'title' => 'Frontend Refactor Challenge',
                'description' => 'Improve or rebuild a small UI component daily using Vue 3.',
            ],
            [
                'title' => 'Database Query Tuning',
                'description' => 'Optimize one query or index per day for better performance.',
            ],
            [
                'title' => 'Testing Discipline',
                'description' => 'Write or refactor at least one test case every day.',
            ],
        ];

        foreach ($challenges as $data) {
            Challenge::factory()->create([
                'title' => $data['title'],
                'description' => $data['description'],
            ]);
        }
    }
}
<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {

        $courses = [
            [
                'title' => 'Computer Science Fundamentals',
                'author' => 'SkillSync Academy',
            ],
            [
                'title' => 'Operating Systems & Networking Basics',
                'author' => 'SkillSync Academy',
            ],
            [
                'title' => 'Algorithms & Data Structures',
                'author' => 'DevPath Institute',
            ],
            [
                'title' => 'Relational Databases & SQL',
                'author' => 'FullStack Labs',
            ],
            [
                'title' => 'Laravel Backend Development',
                'author' => 'SkillSync Academy',
            ],
            [
                'title' => 'Vue 3 Frontend Fundamentals',
                'author' => 'Frontline UI School',
            ],
            [
                'title' => 'REST API Design & Testing',
                'author' => 'DevPath Institute',
            ],
            [
                'title' => 'Docker & Environment Management',
                'author' => 'OpsReady Academy',
            ],
        ];
        foreach ($courses as $data) {
            Course::factory()->create([
                'title'     => $data['title'],
                'author'    => $data['author'],
                'image_url' => null, 
            ]);
        }
    }
}


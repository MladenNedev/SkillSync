<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\UserCourseProgress;
use Carbon\Carbon;

class UserCourseProgressSeeder extends Seeder
{
    public function run(): void
    {

        $user = User::where('email', 'demo@skillsync.test')->firstOrFail();

        $progressMap = [
            'Computer Science Fundamentals'         => 100,
            'Operating Systems & Networking Basics' => 80,
            'Algorithms & Data Structures'          => 60,
            'Relational Databases & SQL'            => 40,
            'Laravel Backend Development'           => 30,
            'Vue 3 Frontend Fundamentals'           => 20,
            'REST API Design & Testing'             => 10,
            'Docker & Environment Management'       => 0,
        ];

        foreach ($progressMap as $courseTitle => $percent) {
            $course = Course::where('title', $courseTitle)->first();

            if (! $course) {
                continue;
            }

            $status = $percent === 100 ? 'completed' : ($percent > 0 ? 'in_progress' : 'not_started');

            UserCourseProgress::create([
                'user_id'          => $user->id,
                'course_id'        => $course->id,
                'progress_percent' => $percent,
                'status'           => $status,
                'last_accessed_at' => Carbon::now()->subDays(7 - (int) floor($percent / 15)),
            ]);
        }
        
    }
}
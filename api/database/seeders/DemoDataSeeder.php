<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Challenge;
use App\Models\UserCourseProgress;
use App\Models\UserChallengeProgress;
use App\Models\StudySession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@skillsync.test'],
            [
                'name'     => 'Demo User',
                'username' => 'demo',
                'password' => Hash::make('password'),
            ]
        );

        // Courses
        $coursesData = [
            [
                'title'           => 'Intro to HTML & CSS',
                'author'          => 'SkillSync Academy',
                'image_url'       => null,
                'description'     => 'Fundamentals of building static web pages.',
                'estimated_hours' => 6,
                'difficulty'      => 'beginner',
            ],
            [
                'title'           => 'JavaScript Basics',
                'author'          => 'SkillSync Academy',
                'image_url'       => null,
                'description'     => 'Core JS syntax, variables, functions and DOM.',
                'estimated_hours' => 8,
                'difficulty'      => 'beginner',
            ],
            [
                'title'           => 'Vue 3 Fundamentals',
                'author'          => 'SkillSync Academy',
                'image_url'       => null,
                'description'     => 'SPA fundamentals with Vue 3 and components.',
                'estimated_hours' => 10,
                'difficulty'      => 'intermediate',
            ],
            [
                'title'           => 'Laravel API Design',
                'author'          => 'SkillSync Academy',
                'image_url'       => null,
                'description'     => 'Building clean REST APIs with Laravel.',
                'estimated_hours' => 12,
                'difficulty'      => 'intermediate',
            ],
        ];

        $courses = collect($coursesData)->map(fn ($data) => Course::create($data));

        // Challenges
        $challengesData = [
            [
                'title'             => 'Flexbox Layout Challenge',
                'description'       => 'Rebuild a given layout using flexbox.',
                'difficulty'        => 'beginner',
                'estimated_minutes' => 45,
            ],
            [
                'title'             => 'JS Array Methods Drill',
                'description'       => 'Practice map/filter/reduce on example data.',
                'difficulty'        => 'beginner',
                'estimated_minutes' => 60,
            ],
            [
                'title'             => 'API Integration Mini Project',
                'description'       => 'Consume a public JSON API and display data.',
                'difficulty'        => 'intermediate',
                'estimated_minutes' => 90,
            ],
        ];

        $challenges = collect($challengesData)->map(fn ($data) => Challenge::create($data));

        // Course progress for the demo user
        $now = Carbon::now();

        foreach ($courses as $index => $course) {
            $status = $index < 2 ? 'completed' : 'in_progress';
            $progressPercent = $status === 'completed' ? 100 : ($index === 2 ? 60 : 30);

            UserCourseProgress::create([
                'user_id'             => $user->id,
                'course_id'           => $course->id,
                'status'              => $status,
                'progress_percent'    => $progressPercent,
                'last_accessed_at'    => $now->copy()->subDays(rand(0, 3)),
                'total_minutes_spent' => rand(120, 480),
                'completed_at'        => $status === 'completed'
                    ? $now->copy()->subDays(rand(4, 10))
                    : null,
            ]);
        }

        // Challenge progress
        foreach ($challenges as $index => $challenge) {
            $status = $index === 0 ? 'completed' : 'in_progress';
            $progressPercent = $status === 'completed' ? 100 : rand(20, 80);

            UserChallengeProgress::create([
                'user_id'             => $user->id,
                'challenge_id'        => $challenge->id,
                'status'              => $status,
                'progress_percent'    => $progressPercent,
                'last_accessed_at'    => $now->copy()->subDays(rand(0, 3)),
                'total_minutes_spent' => rand(30, 180),
                'completed_at'        => $status === 'completed'
                    ? $now->copy()->subDays(rand(4, 10))
                    : null,
            ]);
        }

        // Study sessions for last 7 days
        $weekStart = $now->copy()->subDays(6)->startOfDay();

        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);

            // One course session
            $course = $courses->random();
            $courseMinutes = rand(30, 120);
            $courseStart = $day->copy()->setTime(rand(9, 20), 0);
            $courseEnd   = $courseStart->copy()->addMinutes($courseMinutes);

            StudySession::create([
                'user_id'          => $user->id,
                'course_id'        => $course->id,
                'challenge_id'     => null,
                'type'             => 'course',
                'started_at'       => $courseStart,
                'ended_at'         => $courseEnd,
                'duration_minutes' => $courseMinutes,
            ]);

            // One challenge session
            $challenge = $challenges->random();
            $challengeMinutes = rand(15, 60);
            $challengeStart   = $day->copy()->setTime(rand(10, 22), 0);
            $challengeEnd     = $challengeStart->copy()->addMinutes($challengeMinutes);

            StudySession::create([
                'user_id'          => $user->id,
                'course_id'        => null,
                'challenge_id'     => $challenge->id,
                'type'             => 'challenge',
                'started_at'       => $challengeStart,
                'ended_at'         => $challengeEnd,
                'duration_minutes' => $challengeMinutes,
            ]);
        }
    }
}
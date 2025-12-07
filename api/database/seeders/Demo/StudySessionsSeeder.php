<?php

namespace Database\Seeders\Demo;

use App\Models\Challenge;
use App\Models\Course;
use App\Models\StudySession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StudySessionsSeeder extends Seeder
{
    private function seedWeeklySessions(
        User $user,
        string $type,    // 'course' or 'challenge'
        ?int $courseId,
        ?int $challengeId,
        array $hoursPerDay
    ): void {
        $monday = Carbon::now()->startOfWeek(Carbon::MONDAY);

        foreach ($hoursPerDay as $i => $hours) {
            if ($hours <= 0) {
                continue;
            }

            $start = $monday->copy()->addDays($i)->setTime(10, 0);
            $durationMinutes = (int) round($hours * 60);

            StudySession::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'challenge_id' => $challengeId,
                'type' => $type,
                'started_at' => $start,
                'ended_at' => $start->copy()->addMinutes($durationMinutes),
                'duration_minutes' => $durationMinutes,
            ]);
        }
    }

    public function run(): void
    {
        $user = User::where('email', 'demo@skillsync.test')->firstOrFail();

        // Ensure course exists
        $course = Course::where('title', 'JavaScript Basics')->first()
            ?? Course::first();

        // Ensure challenge exists for the user
        $challenge = $user->challenges()->first()
            ?? Challenge::first()
            ?? Challenge::factory()->create([
                'title' => 'Demo Challenge',
                'description' => 'Automatically generated demo challenge',
            ]);

        // Weekly Course Study
        $this->seedWeeklySessions(
            user: $user,
            type: 'course',
            courseId: $course->id,
            challengeId: null,
            hoursPerDay: [1.2, 0.8, 2.1, 1.7, 0.5, 1.0, 2.8]
        );

        // Weekly Challenge Study
        $this->seedWeeklySessions(
            user: $user,
            type: 'challenge',
            courseId: null,
            challengeId: $challenge->id,
            hoursPerDay: [0.5, 1.0, 0.2, 0.7, 1.5, 0.0, 0.9]
        );
    }
}
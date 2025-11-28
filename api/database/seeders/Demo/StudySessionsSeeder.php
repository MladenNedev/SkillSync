<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\StudySession;
use Carbon\Carbon;

class StudySessionsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@skillsync.test')->firstOrFail();
        $course = Course::where('title', 'JavaScript Basics')->first()
            ?? Course::first();

        $monday = Carbon::now()->startOfWeek(Carbon::MONDAY);

        $hoursPerDay = [1.2, 0.8, 2.1, 1.7, 0.5, 1.0, 2.8];

        foreach ($hoursPerDay as $i => $hours) {
            $start = $monday->copy()->addDays($i)->setTime(10, 0);

            $durationMinutes = (int) round($hours * 60);

            StudySession::create([
                'user_id'          => $user->id,
                'course_id'        => $course?->id,
                'challenge_id'     => null,
                'type'             => 'course',
                'started_at'       => $start,
                'ended_at'         => $start->copy()->addMinutes($durationMinutes),
                'duration_minutes' => $durationMinutes,
            ]);
        }
    }
}
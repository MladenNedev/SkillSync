<?php

namespace Database\Factories;

use App\Models\StudySession;
use App\Models\User;
use App\Models\Course;
use App\Models\Challenge;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class StudySessionFactory extends Factory
{
    protected $model = StudySession::class;

    public function definition(): array
    {

        $start = Carbon::now()
            ->subDays($this->faker->numberBetween(0, 6))
            ->setTime($this->faker->numberBetween(8, 20), 0);

        $durationMinutes = $this->faker->numberBetween(20, 120);

        return [
            'user_id'          => User::factory(),
            'course_id'        => null,
            'challenge_id'     => null,
            'type'             => 'course',
            'started_at'       => $start,
            'ended_at'         => (clone $start)->addMinutes($durationMinutes),
            'duration_minutes' => $durationMinutes,
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }

    public function forCourse(Course $course): static
    {
        return $this->state(fn () => [
            'course_id'    => $course->id,
            'challenge_id' => null,
            'type'         => 'course',
        ]);
    }

    public function forChallenge(Challenge $challenge): static
    {
        return $this->state(fn () => [
            'course_id'    => null,
            'challenge_id' => $challenge->id,
            'type'         => 'challenge',
        ]);
    }


}

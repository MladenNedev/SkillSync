<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            Demo\UsersSeeder::class,
            Demo\CoursesSeeder::class,
            Demo\ChallengesSeeder::class,
            Demo\UserCourseProgressSeeder::class,
            Demo\UserChallengeProgressSeeder::class,
            Demo\StudySessionsSeeder::class,
        ]);
    }
}

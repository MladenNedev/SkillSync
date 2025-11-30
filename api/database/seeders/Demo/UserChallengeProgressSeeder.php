<?php

namespace Database\Seeders\Demo;

use App\Models\Challenge;
use App\Models\User;
use App\Models\UserChallengeProgress;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserChallengeProgressSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@skillsync.test')->firstOrFail();

        $challenges = Challenge::all()->values();

        $progressOptions = [
            ['percent' => 100, 'status' => 'completed'],
            ['percent' => 60,  'status' => 'in_progress'],
            ['percent' => 20,  'status' => 'in_progress'],
        ];

        foreach ($challenges as $index => $challenge) {
            $config = $progressOptions[$index % count($progressOptions)];

            $completedAt = $config['percent'] === 100
                ? Carbon::now()->subDays(3)
                : null;

            UserChallengeProgress::create([
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'progress_percent' => $config['percent'],
                'status' => $config['status'],
                'last_accessed_at' => Carbon::now()->subDays(2),
                'completed_at' => $completedAt,
            ]);
        }
    }
}

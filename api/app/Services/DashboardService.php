<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCourseProgress;
use Carbon\Carbon;

class DashboardService
{
    public function getSummaryForUser(User $user): array
    {
        $now = Carbon::now();

        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();

        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();

        $lastWeekStart = $weekStart->copy()->subWeek();
        $lastWeekEnd = $weekEnd->copy()->subWeek();

        // === Counts ===
        $coursesInProgress = $user->courseProgress()->where('status', 'in_progress')->count();
        $coursesCompleted = $user->courseProgress()->where('status', 'completed')->count();
        $challengesCompleted = $user->challengeProgress()->where('status', 'completed')->count();

        // === Time today ===
        $minutesToday = $user->studySessions()
            ->whereBetween('started_at', [$todayStart, $todayEnd])
            ->sum('duration_minutes');

        // === Time this week & last week ===
        $minutesThisWeek = $user->studySessions()
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->sum('duration_minutes');

        $minutesLastWeek = $user->studySessions()
            ->whereBetween('started_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('duration_minutes');

        // === Course vs challenge this week ===
        $courseMinutesThisWeek = $user->studySessions()
            ->where('type', 'course')
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->sum('duration_minutes');

        $challengeMinutesThisWeek = $user->studySessions()
            ->where('type', 'challenge')
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->sum('duration_minutes');

        $avgMinutesPerDay = $minutesThisWeek / 7;

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'stats' => [
                'courses_in_progress' => $coursesInProgress,
                'completed_courses' => $coursesCompleted,
                'completed_challenges' => $challengesCompleted,
                'time_spent_today_minutes' => (int) $minutesToday,
            ],
            'weekly' => [
                'total_hours' => round($minutesThisWeek / 60, 1),
                'average_hours_per_day' => round($avgMinutesPerDay / 60, 2),
                'course_hours' => round($courseMinutesThisWeek / 60, 1),
                'challenge_hours' => round($challengeMinutesThisWeek / 60, 1),
                'delta' => [
                    'total_hours' => $this->calculateDeltaPercent($minutesThisWeek, $minutesLastWeek),
                ],
                'range' => [
                    'start' => $weekStart->toDateString(),
                    'end' => $weekEnd->toDateString(),
                ],
            ],
        ];
    }

    public function getChartDataForUser(User $user): array
    {
        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();

        $rows = $user->studySessions()
            ->selectRaw('DATE(started_at) as date, type, SUM(duration_minutes) as total_minutes')
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $byDate = [];

        foreach ($rows as $row) {
            $date = $row->date;
            if (! isset($byDate[$date])) {
                $byDate[$date] = [
                    'course_minutes' => 0,
                    'challenge_minutes' => 0,
                ];
            }

            if ($row->type === 'course') {
                $byDate[$date]['course_minutes'] = (int) $row->total_minutes;
            } elseif ($row->type === 'challenge') {
                $byDate[$date]['challenge_minutes'] = (int) $row->total_minutes;
            }
        }

        $days = [];
        $cursor = $weekStart->copy();

        for ($i = 0; $i < 7; $i++) {
            $dateStr = $cursor->toDateString();

            $days[] = array_merge(
                ['date' => $dateStr],
                $byDate[$dateStr] ?? [
                    'course_minutes' => 0,
                    'challenge_minutes' => 0,
                ]
            );

            $cursor->addDay();
        }

        return [
            'range' => [
                'start_date' => $weekStart->toDateString(),
                'end_date' => $weekEnd->toDateString(),
            ],
            'days' => $days,
        ];
    }

    public function getRecentCoursesForUser(User $user, int $limit = 3): array
    {
        $progress = $user->courseProgress()
            ->with('course')
            ->orderByDesc('last_accessed_at')
            ->limit($limit)
            ->get();

        return $progress
            ->filter(fn (UserCourseProgress $item) => $item->course !== null)
            ->map(function (UserCourseProgress $item) {
                $course = $item->course;

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'author' => $course->author,
                    'image_url' => $course->image_url,
                    'progress_percent' => $item->progress_percent,
                    'last_accessed_at' => $item->last_accessed_at,
                ];
            })
            ->values()
            ->all();
    }

    protected function calculateDeltaPercent(float|int $current, float|int $previous): float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}

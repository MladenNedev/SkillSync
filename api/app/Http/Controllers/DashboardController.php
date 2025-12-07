<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboard
    ) {}

    public function summary(Request $request)
    {
        $user = $request->user(); // auth()->user()

        return response()->json(
            $this->dashboard->getSummaryForUser($user)
        );
    }

    public function chart(Request $request)
    {
        $user = $request->user();

        return response()->json(
            $this->dashboard->getChartDataForUser($user)
        );
    }

    public function recentCourses(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => $this->dashboard->getRecentCoursesForUser($user),
        ]);
    }
}

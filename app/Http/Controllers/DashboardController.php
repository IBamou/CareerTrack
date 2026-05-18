<?php

namespace App\Http\Controllers;

use App\Enums\JobApplicationStatus;
use App\Models\Company;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'applications' => JobApplication::where('applied_by', $userId)->count(),
            'companies' => Company::where('user_id', $userId)->count(),
            'active' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [JobApplicationStatus::Applied, JobApplicationStatus::InReview])
                ->count(),
            'interviews' => Interview::where('user_id', $userId)
                ->where('scheduled_at', '>=', now())
                ->count(),
        ];

        $recentApplications = JobApplication::with('company')
            ->where('applied_by', $userId)
            ->latest()
            ->take(5)
            ->get();

        $monthly = JobApplication::where('applied_by', $userId)
            ->selectRaw('YEAR(applied_at) as year, MONTH(applied_at) as month, COUNT(*) as count')
            ->whereNotNull('applied_at')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $statusDistribution = JobApplication::where('applied_by', $userId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $upcomingReminders = Reminder::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('remind_at', '>=', now())
            ->orderBy('remind_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentApplications', 'monthly', 'statusDistribution', 'upcomingReminders'));
    }
}

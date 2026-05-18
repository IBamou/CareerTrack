<?php

namespace App\Http\Controllers;

use App\Enums\JobApplicationStatus;
use App\Models\Company;
use App\Models\JobApplication;
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
            'interviews' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [
                    JobApplicationStatus::HrInterview,
                    JobApplicationStatus::TechnicalInterview,
                    JobApplicationStatus::FinalInterview,
                ])
                ->count(),
        ];

        $recentApplications = JobApplication::with('company')
            ->where('applied_by', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentApplications'));
    }
}

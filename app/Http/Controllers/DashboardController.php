<?php

namespace App\Http\Controllers;

use App\Enums\JobApplicationStatus;
use App\Models\Company;
use App\Models\Interview;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $totalApps = JobApplication::where('applied_by', $userId)->count();

        $stats = [
            'total' => $totalApps,
            'in_progress' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [JobApplicationStatus::Applied, JobApplicationStatus::InReview])
                ->count(),
            'interviews' => Interview::where('user_id', $userId)
                ->where('scheduled_at', '>=', now())
                ->count(),
            'offers' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [JobApplicationStatus::Offer, JobApplicationStatus::Accepted])
                ->count(),
            'rejections' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [JobApplicationStatus::Rejected, JobApplicationStatus::Ghosted])
                ->count(),
            'companies' => Company::where('user_id', $userId)->count(),
            'applied' => JobApplication::where('applied_by', $userId)->where('status', JobApplicationStatus::Applied)->count(),
            'screening' => JobApplication::where('applied_by', $userId)->where('status', JobApplicationStatus::InReview)->count(),
            'interviewing' => JobApplication::where('applied_by', $userId)->whereIn('status', [
                JobApplicationStatus::HrInterview, JobApplicationStatus::TechnicalInterview, JobApplicationStatus::FinalInterview
            ])->count(),
            'offer_count' => JobApplication::where('applied_by', $userId)->where('status', JobApplicationStatus::Offer)->count(),
            'rejected_count' => JobApplication::where('applied_by', $userId)->where('status', JobApplicationStatus::Rejected)->count(),
        ];

        $recentApplications = JobApplication::with('company')
            ->where('applied_by', $userId)
            ->latest()
            ->take(5)
            ->get();

        $upcomingEvents = Interview::with('jobApplication.company')
            ->where('user_id', $userId)
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $userName = Auth::user()->name;
        $userInitial = strtoupper(substr($userName, 0, 1));

        return view('dashboard', compact('stats', 'recentApplications', 'upcomingEvents', 'userName', 'userInitial'));
    }

    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'status' => 'nullable|string|in:applied,in_review,hr_interview,technical_interview,final_interview,offer,accepted,rejected,ghosted',
        ]);

        $company = Company::firstOrCreate(
            ['name' => $validated['company_name'], 'user_id' => Auth::id()],
            ['name' => $validated['company_name'], 'user_id' => Auth::id()]
        );

        $application = JobApplication::create([
            'job_title' => $validated['job_title'],
            'company_id' => $company->id,
            'status' => $validated['status'] ?? JobApplicationStatus::Applied,
            'applied_by' => Auth::id(),
            'applied_at' => now(),
        ]);

        return redirect()->route('job-applications.show', $application)
            ->with('status', 'Application added successfully.');
    }
}

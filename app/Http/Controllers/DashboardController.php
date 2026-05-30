<?php

namespace App\Http\Controllers;

use App\Enums\JobApplicationStatus;
use App\Models\Company;
use App\Models\Interview;
use App\Models\JobApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $currentMonth = now()->startOfMonth();

        $statusCounts = JobApplication::where('applied_by', $userId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalApps = $statusCounts->sum();
        $applied = $statusCounts->get('applied', 0);
        $inReview = $statusCounts->get('in_review', 0);
        $interviewing = $statusCounts->get('hr_interview', 0)
            + $statusCounts->get('technical_interview', 0)
            + $statusCounts->get('final_interview', 0);
        $offerCount = $statusCounts->get('offer', 0);
        $acceptedCount = $statusCounts->get('accepted', 0);
        $rejectedCount = $statusCounts->get('rejected', 0);
        $ghostedCount = $statusCounts->get('ghosted', 0);

        $stats = [
            'total' => $totalApps,
            'in_progress' => $applied + $inReview,
            'interviews' => Interview::where('user_id', $userId)
                ->where('scheduled_at', '>=', now())
                ->count(),
            'offers' => $offerCount + $acceptedCount,
            'rejections' => $rejectedCount + $ghostedCount,
            'applied' => $applied,
            'screening' => $inReview,
            'interviewing' => $interviewing,
            'offer_count' => $offerCount,
            'rejected_count' => $rejectedCount,
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

        $calendarEventDates = Interview::where('user_id', $userId)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>=', $currentMonth->copy()->subMonth())
            ->pluck('scheduled_at')
            ->map(fn ($d) => $d->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $userName = Auth::user()->name;
        $userInitial = strtoupper(substr($userName, 0, 1));

        return view('dashboard', compact(
            'stats', 'recentApplications', 'upcomingEvents', 'calendarEventDates',
            'userName', 'userInitial', 'currentMonth'
        ));
    }

    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'status' => ['nullable', 'string', new Enum(JobApplicationStatus::class)],
        ]);

        $company = Company::firstOrCreate(
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

    public function calendarGrid(Request $request)
    {
        $userId = Auth::id();

        $monthParam = $request->query('month');
        $currentMonth = $monthParam ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth() : now()->startOfMonth();

        $calendarEventDates = Interview::where('user_id', $userId)
            ->whereNotNull('scheduled_at')
            ->whereBetween('scheduled_at', [
                $currentMonth->copy()->startOfMonth()->startOfWeek(0),
                $currentMonth->copy()->endOfMonth()->endOfWeek(6),
            ])
            ->pluck('scheduled_at')
            ->map(fn ($d) => $d->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $today = now();
        $firstDay = $currentMonth->copy()->startOfMonth();
        $lastDay = $currentMonth->copy()->endOfMonth();
        $startOfWeek = $firstDay->copy()->startOfWeek(0);
        $endOfWeek = $lastDay->copy()->endOfWeek(6);

        $html = '';
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $isToday = $date->isSameDay($today);
            $isCurrentMonth = $date->month === $currentMonth->month && $date->year === $currentMonth->year;
            $hasEvent = in_array($date->format('Y-m-d'), $calendarEventDates);
            $day = $date->day;

            $html .= '<div class="flex justify-center">';

            if ($isToday) {
                $html .= '<div class="relative text-sm font-bold text-white bg-[#2563eb] w-8 h-8 rounded-full flex items-center justify-center shadow-sm">';
                $html .= $day;
                if ($hasEvent) {
                    $html .= '<span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-white rounded-full"></span>';
                }
                $html .= '</div>';
            } else {
                $classes = $isCurrentMonth
                    ? 'text-slate-700 dark:text-slate-300'
                    : 'text-slate-300 dark:text-slate-600';
                $html .= '<div class="relative text-sm font-semibold '.$classes.' w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">';
                $html .= $day;
                if ($hasEvent) {
                    $html .= '<span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-[#2563eb] dark:bg-blue-400 rounded-full"></span>';
                }
                $html .= '</div>';
            }

            $html .= '</div>';
        }

        return response()->json([
            'html' => $html,
            'label' => $currentMonth->format('F Y'),
        ]);
    }
}

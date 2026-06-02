<?php

namespace App\Http\Controllers;

use App\Enums\ReminderStatus;
use App\Http\Requests\Reminder\StoreReminderRequest;
use App\Http\Requests\Reminder\UpdateReminderRequest;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $month = now()->month;
        $year = now()->year;

        $events = $this->getEvents($month, $year);
        $interviewsList = $this->getInterviewsList();
        $applicationsList = $this->getApplicationsList();

        return view('calendar.index', compact('events', 'month', 'year', 'interviewsList', 'applicationsList'));
    }

    private function getInterviewsList(): array
    {
        return Interview::with('jobApplication.company')
            ->where('user_id', Auth::id())
            ->whereNotNull('scheduled_at')
            ->get()
            ->map(fn ($i) => [
                'id' => $i->id,
                'label' => ($i->jobApplication?->job_title ?? 'Interview').' — '.($i->jobApplication?->company?->name ?? 'No company'),
                'type' => 'App\Models\Interview',
            ])
            ->values()
            ->toArray();
    }

    private function getApplicationsList(): array
    {
        return JobApplication::with('company')
            ->where('applied_by', Auth::id())
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'label' => $a->job_title.' — '.($a->company?->name ?? 'No company'),
                'type' => 'App\Models\JobApplication',
            ])
            ->values()
            ->toArray();
    }

    public function events(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $month = max(1, min(12, $month));
        $year = max(2000, min(2100, $year));

        return response()->json($this->getEvents($month, $year));
    }

    private function getEvents(int $month, int $year): array
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        $userId = Auth::id();

        $interviews = Interview::with('jobApplication.company')
            ->where('user_id', $userId)
            ->whereNotNull('scheduled_at')
            ->whereDate('scheduled_at', '>=', $startOfMonth)
            ->whereDate('scheduled_at', '<=', $endOfMonth)
            ->get()
            ->map(fn ($i) => [
                'id' => $i->id,
                'title' => $i->jobApplication?->job_title ?? 'Interview',
                'company' => optional($i->jobApplication?->company)->name,
                'date' => $i->scheduled_at->format('Y-m-d'),
                'time' => $i->scheduled_at->format('H:i'),
                'type' => 'interview',
                'url' => route('interviews.show', $i),
            ]);

        $applications = JobApplication::with('company')
            ->where('applied_by', $userId)
            ->whereDate('applied_at', '>=', $startOfMonth)
            ->whereDate('applied_at', '<=', $endOfMonth)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'title' => 'Applied: '.$a->job_title,
                'company' => optional($a->company)->name,
                'date' => $a->applied_at->format('Y-m-d'),
                'time' => null,
                'type' => 'application',
                'url' => route('job-applications.show', $a),
            ]);

        $reminders = Reminder::with('remindable')
            ->where('user_id', $userId)
            ->where('status', ReminderStatus::Pending)
            ->whereDate('remind_at', '>=', $startOfMonth)
            ->whereDate('remind_at', '<=', $endOfMonth)
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'title' => $r->title,
                'description' => $r->description,
                'company' => null,
                'date' => $r->remind_at->format('Y-m-d'),
                'time' => $r->remind_at->format('H:i'),
                'type' => 'reminder',
                'url' => null,
                'remindable_type' => $r->remindable_type,
                'remindable_id' => $r->remindable_id,
                'remindable_label' => $r->remindable ? $this->getRemindableLabel($r->remindable) : null,
            ]);

        $grouped = collect($interviews)
            ->concat($applications)
            ->concat($reminders)
            ->groupBy('date')
            ->map(fn ($items, $date) => [
                'date' => $date,
                'items' => $items,
            ])
            ->values()
            ->toArray();

        return $grouped;
    }

    public function storeReminder(StoreReminderRequest $request)
    {
        if ($request->remindable_type && $request->remindable_id) {
            $modelClass = $request->remindable_type;
            $ownerColumn = $modelClass === Interview::class ? 'user_id' : 'applied_by';
            $exists = $modelClass::where('id', $request->remindable_id)->where($ownerColumn, Auth::id())->exists();
            if (! $exists) {
                return response()->json(['message' => 'The specified entity does not exist or belongs to another user.'], 403);
            }
        }

        $reminder = Reminder::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'remind_at' => $request->remind_at,
            'status' => ReminderStatus::Pending,
            'remindable_type' => $request->remindable_type,
            'remindable_id' => $request->remindable_id,
        ]);

        return response()->json([
            'success' => true,
            'reminder' => [
                'id' => $reminder->id,
                'title' => $reminder->title,
                'description' => $reminder->description,
                'remind_at' => $reminder->remind_at->format('Y-m-d H:i'),
                'remindable_type' => $reminder->remindable_type,
                'remindable_id' => $reminder->remindable_id,
            ],
        ]);
    }

    public function updateReminder(UpdateReminderRequest $request, Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        if ($request->remindable_type && $request->remindable_id) {
            $modelClass = $request->remindable_type;
            $ownerColumn = $modelClass === Interview::class ? 'user_id' : 'applied_by';
            $exists = $modelClass::where('id', $request->remindable_id)->where($ownerColumn, Auth::id())->exists();
            if (! $exists) {
                return response()->json(['message' => 'The specified entity does not exist or belongs to another user.'], 403);
            }
        }

        $reminder->update($request->only(['title', 'description', 'remind_at', 'remindable_type', 'remindable_id']));

        return response()->json([
            'success' => true,
            'reminder' => [
                'id' => $reminder->id,
                'title' => $reminder->title,
                'description' => $reminder->description,
                'remind_at' => $reminder->remind_at->format('Y-m-d H:i'),
                'remindable_type' => $reminder->remindable_type,
                'remindable_id' => $reminder->remindable_id,
            ],
        ]);
    }

    public function completeReminder(Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $reminder->update(['status' => ReminderStatus::Sent, 'reminded_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroyReminder(Reminder $reminder)
    {
        $this->authorize('delete', $reminder);

        $reminder->delete();

        return response()->json(['success' => true]);
    }

    private function getRemindableLabel($remindable): ?string
    {
        if ($remindable instanceof Interview) {
            $jobTitle = $remindable->jobApplication?->job_title ?? 'Interview';
            $company = $remindable->jobApplication?->company?->name;

            return $company ? "{$jobTitle} at {$company}" : $jobTitle;
        }

        if ($remindable instanceof JobApplication) {
            $company = $remindable->company?->name;

            return $company ? "{$remindable->job_title} at {$company}" : $remindable->job_title;
        }

        return null;
    }
}

<?php

namespace App\Http\Controllers;

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

        return view('calendar.index', compact('events', 'month', 'year'));
    }

    public function events(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

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
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereDate('applied_at', '>=', $startOfMonth)
                  ->whereDate('applied_at', '<=', $endOfMonth)
                  ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                      $q2->whereNotNull('next_follow_up_at')
                         ->whereDate('next_follow_up_at', '>=', $startOfMonth)
                         ->whereDate('next_follow_up_at', '<=', $endOfMonth);
                  });
            })
            ->get()
            ->flatMap(fn ($a) => collect([
                $a->applied_at && $a->applied_at->between($startOfMonth, $endOfMonth) ? [
                    'id' => $a->id,
                    'title' => 'Applied: ' . $a->job_title,
                    'company' => optional($a->company)->name,
                    'date' => $a->applied_at->format('Y-m-d'),
                    'time' => null,
                    'type' => 'application',
                    'url' => route('job-applications.show', $a),
                ] : null,
                $a->next_follow_up_at && $a->next_follow_up_at->between($startOfMonth, $endOfMonth) ? [
                    'id' => $a->id,
                    'title' => 'Follow up: ' . $a->job_title,
                    'company' => optional($a->company)->name,
                    'date' => $a->next_follow_up_at->format('Y-m-d'),
                    'time' => null,
                    'type' => 'follow_up',
                    'url' => route('job-applications.show', $a),
                ] : null,
            ])->filter());

        $reminders = Reminder::with('remindable')
            ->where('user_id', $userId)
            ->where('status', 'pending')
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

    public function storeReminder(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'remind_at' => 'required|date',
            'remindable_type' => 'nullable|string',
            'remindable_id' => 'nullable|integer',
        ]);

        $reminder = Reminder::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'remind_at' => $validated['remind_at'],
            'remindable_type' => $validated['remindable_type'] ?? null,
            'remindable_id' => $validated['remindable_id'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'reminder' => $reminder]);
    }

    public function completeReminder(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->update(['status' => 'sent', 'reminded_at' => now()]);

        return response()->json(['success' => true]);
    }
}

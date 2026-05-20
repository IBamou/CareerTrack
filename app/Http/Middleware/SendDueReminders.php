<?php

namespace App\Http\Middleware;

use App\Models\Reminder;
use App\Notifications\ReminderDue;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SendDueReminders
{
    public function handle(Request $request, Closure $next): Response
    {
        $reminders = Reminder::where('status', 'pending')
            ->where('remind_at', '<=', now())
            ->whereNull('reminded_at')
            ->get();

        foreach ($reminders as $reminder) {
            $reminder->user->notify(new ReminderDue($reminder));

            $reminder->update([
                'status' => 'sent',
                'reminded_at' => now(),
            ]);
        }

        return $next($request);
    }
}

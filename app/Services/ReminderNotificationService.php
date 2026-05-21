<?php

namespace App\Services;

use App\Models\Reminder;
use App\Notifications\ReminderDue;
use Illuminate\Support\Facades\Log;

class ReminderNotificationService
{
    public function sendDueReminders(): void
    {
        $reminders = Reminder::with('user')
            ->where('status', 'pending')
            ->where('remind_at', '<=', now())
            ->whereNull('reminded_at')
            ->get();

        foreach ($reminders as $reminder) {
            try {
                if (!$reminder->user) {
                    $reminder->update(['status' => 'sent', 'reminded_at' => now()]);
                    continue;
                }

                $reminder->user->notify(new ReminderDue($reminder));

                $reminder->update([
                    'status' => 'sent',
                    'reminded_at' => now(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to send reminder notification', [
                    'reminder_id' => $reminder->id,
                    'user_id' => $reminder->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

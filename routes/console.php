<?php

use App\Models\Reminder;
use App\Notifications\ReminderDue;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
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
})->name('send-due-reminders')->everyMinute();

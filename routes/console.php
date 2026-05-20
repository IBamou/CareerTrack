<?php

use App\Jobs\SendReminderNotification;
use App\Models\Reminder;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $reminders = Reminder::where('status', 'pending')
        ->where('remind_at', '<=', now())
        ->whereNull('reminded_at')
        ->get();

    foreach ($reminders as $reminder) {
        SendReminderNotification::dispatch($reminder);
    }
})->name('send-due-reminders')->everyMinute();

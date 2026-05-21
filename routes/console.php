<?php

use App\Services\ReminderNotificationService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    app(ReminderNotificationService::class)->sendDueReminders();
})->name('send-due-reminders')->everyMinute()->withoutOverlapping();

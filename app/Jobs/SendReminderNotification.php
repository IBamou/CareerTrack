<?php

namespace App\Jobs;

use App\Models\Reminder;
use App\Notifications\ReminderDue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendReminderNotification implements ShouldQueue
{
    use Queueable;

    public Reminder $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function handle(): void
    {
        if ($this->reminder->status !== 'pending') {
            return;
        }

        $this->reminder->user->notify(new ReminderDue($this->reminder));

        $this->reminder->update([
            'status' => 'sent',
            'reminded_at' => now(),
        ]);
    }
}

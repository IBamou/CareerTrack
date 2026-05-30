<?php

namespace App\Notifications;

use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderDue extends Notification implements ShouldQueue
{
    use Queueable;

    public Reminder $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reminder = $this->reminder;

        $relatedInfo = null;
        if ($reminder->remindable_type && $reminder->remindable_id) {
            $remindable = $reminder->remindable;
            if ($remindable instanceof JobApplication) {
                $company = $remindable->company;
                $relatedInfo = $company
                    ? "{$remindable->job_title} at {$company->name}"
                    : $remindable->job_title;
            } elseif ($remindable instanceof Interview) {
                $jobApp = $remindable->jobApplication;
                $relatedInfo = $jobApp
                    ? "Interview: {$jobApp->job_title}"
                    : 'Interview';
            }
        }

        return (new MailMessage)
            ->subject("Don't miss this: {$reminder->title}")
            ->view('emails.reminder', [
                'name' => $notifiable->name,
                'title' => $reminder->title,
                'description' => $reminder->description,
                'date' => $reminder->remind_at->format('Y-m-d'),
                'time' => $reminder->remind_at->format('H:i'),
                'url' => route('calendar.index'),
                'relatedInfo' => $relatedInfo,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reminder_id' => $this->reminder->id,
            'title' => $this->reminder->title,
            'description' => $this->reminder->description,
            'remind_at' => $this->reminder->remind_at->format('Y-m-d H:i'),
            'url' => route('calendar.index'),
        ];
    }
}

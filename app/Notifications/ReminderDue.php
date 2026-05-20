<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderDue extends Notification
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
        return (new MailMessage)
            ->subject("Reminder: {$this->reminder->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is a reminder for: **{$this->reminder->title}**")
            ->lineWhen((bool) $this->reminder->description, $this->reminder->description)
            ->line("Scheduled at: {$this->reminder->remind_at->format('Y-m-d H:i')}")
            ->action('View on Calendar', route('calendar.index'))
            ->line('Thank you for using CareerTrack!');
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

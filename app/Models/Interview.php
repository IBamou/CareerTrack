<?php

namespace App\Models;

use App\Enums\InterviewResult;
use App\Enums\InterviewType;
use App\Traits\LogsActivity;
use Database\Factories\InterviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    /** @use HasFactory<InterviewFactory> */
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['job_application_id', 'type', 'scheduled_at', 'notes', 'result', 'user_id'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'type' => InterviewType::class,
        'result' => InterviewResult::class,
    ];

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function reminders(): MorphMany
    {
        return $this->morphMany(Reminder::class, 'remindable');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function activityDisplayName(): string
    {
        return "{$this->type->value} for {$this->jobApplication?->job_title}";
    }
}

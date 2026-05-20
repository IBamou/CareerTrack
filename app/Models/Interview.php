<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Database\Factories\InterviewFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Interview extends Model
{
    /** @use HasFactory<InterviewFactory> */
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['job_application_id', 'type', 'scheduled_at', 'notes', 'result', 'user_id'];

    protected $casts = [
        'scheduled_at' => 'datetime',
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

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function activityDisplayName(): string
    {
        return "{$this->type} for {$this->jobApplication?->job_title}";
    }
}

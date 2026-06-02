<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use App\Enums\JobLocationType;
use App\Traits\LogsActivity;
use Database\Factories\JobApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    /** @use HasFactory<JobApplicationFactory> */
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['job_title', 'salary_min', 'salary_max', 'currency', 'benefits', 'location_type', 'location_city', 'links', 'status', 'priority', 'applied_at', 'notes', 'company_id', 'applied_by'];

    protected $casts = [
        'status' => JobApplicationStatus::class,
        'location_type' => JobLocationType::class,
        'links' => 'array',
        'applied_at' => 'datetime',

        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'job_application_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
        return $this->job_title;
    }
}

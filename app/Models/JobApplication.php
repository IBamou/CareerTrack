<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use App\Enums\JobLocationType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\JobApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    /** @use HasFactory<JobApplicationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['job_title', 'location_type', 'location_city', 'links', 'status', 'priority', 'applied_at', 'next_follow_up_at',  'notes', 'company_id', 'applied_by'];

    protected $casts = [
        'status' => JobApplicationStatus::class,
        'location_type' => JobLocationType::class,
        'links' => 'array',
        'applied_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
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
}

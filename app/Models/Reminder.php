<?php

namespace App\Models;

use App\Enums\ReminderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'remindable_type', 'remindable_id', 'title', 'description', 'remind_at', 'reminded_at', 'status'];

    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
            'reminded_at' => 'datetime',
            'status' => ReminderStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function remindable(): MorphTo
    {
        return $this->morphTo();
    }
}

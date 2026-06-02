<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        foreach (static::getLoggedEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->logActivity($event);
            });
        }
    }

    protected static function getLoggedEvents(): array
    {
        return ['created', 'updated', 'deleted', 'restored'];
    }

    protected function logActivity(string $event): void
    {
        if (! Auth::check()) {
            return;
        }

        $oldValues = match ($event) {
            'updated' => $this->getOriginal(),
            'deleted' => $this->getOriginal(),
            default => null,
        };

        $newValues = match ($event) {
            'created' => $this->getAttributes(),
            'updated' => $this->getChanges(),
            default => null,
        };

        $action = match ($event) {
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => $this->isForceDeleting() ? 'force_deleted' : 'archived',
            'restored' => 'restored',
            default => $event,
        };

        $descriptions = [
            'created' => static::getActivityDescription('created', $this),
            'updated' => static::getActivityDescription('updated', $this),
            'archived' => static::getActivityDescription('archived', $this),
            'force_deleted' => static::getActivityDescription('force_deleted', $this),
            'restored' => static::getActivityDescription('restored', $this),
        ];

        ActivityLog::create([
            'user_id' => Auth::id(),
            'loggable_type' => get_class($this),
            'loggable_id' => $this->getKey(),
            'action' => $action,
            'description' => $descriptions[$action] ?? null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    protected static function getActivityDescription(string $event, $model): ?string
    {
        $name = method_exists($model, 'activityDisplayName') ? $model->activityDisplayName() : ($model->name ?? $model->title ?? "{$event} ".class_basename($model));

        return match ($event) {
            'created' => "Created {$name}",
            'updated' => "Updated {$name}",
            'archived' => "Archived {$name}",
            'force_deleted' => "Permanently deleted {$name}",
            'restored' => "Restored {$name}",
            default => null,
        };
    }
}

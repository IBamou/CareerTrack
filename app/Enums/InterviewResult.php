<?php

namespace App\Enums;

enum InterviewResult: string
{
    case Passed = 'Passed';
    case Rejected = 'Rejected';
    case Cancelled = 'Cancelled';
    case Rescheduled = 'Rescheduled';

    public function label(): string
    {
        return match ($this) {
            self::Passed => 'Passed',
            self::Rejected => 'Rejected',
            self::Cancelled => 'Cancelled',
            self::Rescheduled => 'Rescheduled',
        };
    }
}

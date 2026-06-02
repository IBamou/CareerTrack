<?php

namespace App\Enums;

enum InterviewType: string
{
    case Phone = 'Phone';
    case VideoCall = 'Video Call';
    case Hr = 'HR';
    case Technical = 'Technical';
    case OnSite = 'On-site';

    public function label(): string
    {
        return match ($this) {
            self::Phone => 'Phone',
            self::VideoCall => 'Video Call',
            self::Hr => 'HR',
            self::Technical => 'Technical',
            self::OnSite => 'On-site',
        };
    }
}

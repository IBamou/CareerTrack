<?php

namespace App\Enums;

enum JobApplicationStatus: string
{
    case Applied = 'applied';
    case InReview = 'in_review';
    case HrInterview = 'hr_interview';
    case TechnicalInterview = 'technical_interview';
    case FinalInterview = 'final_interview';
    case Offer = 'offer';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Ghosted = 'ghosted';

    public function label(): string
    {
        return match ($this) {
            self::Applied => 'Applied',
            self::InReview => 'In Review',
            self::HrInterview => 'HR Interview',
            self::TechnicalInterview => 'Technical Interview',
            self::FinalInterview => 'Final Interview',
            self::Offer => 'Offer',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Ghosted => 'Ghosted',
        };
    }
}

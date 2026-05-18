<?php

namespace App\View\Components;

use App\Enums\JobApplicationStatus;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $colorClasses;

    public function __construct(public JobApplicationStatus $status)
    {
        $this->colorClasses = match ($status) {
            JobApplicationStatus::Applied => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            JobApplicationStatus::InReview => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            JobApplicationStatus::HrInterview => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            JobApplicationStatus::TechnicalInterview => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            JobApplicationStatus::FinalInterview => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            JobApplicationStatus::Offer => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            JobApplicationStatus::Accepted => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300',
            JobApplicationStatus::Rejected => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            JobApplicationStatus::Ghosted => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    public function render(): View
    {
        return view('components.status-badge');
    }
}

@props(['status', 'size' => 'sm'])

@php
$sizeClasses = match ($size) {
    'lg' => 'px-3 py-1 text-sm',
    'md' => 'px-2.5 py-1 text-xs',
    default => 'px-2 py-0.5 text-xs',
};

$colorClasses = match ($status) {
    \App\Enums\JobApplicationStatus::Applied => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 ring-1 ring-inset ring-blue-600/20 dark:ring-blue-400/20',
    \App\Enums\JobApplicationStatus::InReview => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 ring-1 ring-inset ring-indigo-600/20 dark:ring-indigo-400/20',
    \App\Enums\JobApplicationStatus::HrInterview => 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 ring-1 ring-inset ring-purple-600/20 dark:ring-purple-400/20',
    \App\Enums\JobApplicationStatus::TechnicalInterview => 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 ring-1 ring-inset ring-orange-600/20 dark:ring-orange-400/20',
    \App\Enums\JobApplicationStatus::FinalInterview => 'bg-pink-50 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400 ring-1 ring-inset ring-pink-600/20 dark:ring-pink-400/20',
    \App\Enums\JobApplicationStatus::Offer => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-400/20',
    \App\Enums\JobApplicationStatus::Accepted => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-400/20',
    \App\Enums\JobApplicationStatus::Rejected => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-400/20',
    \App\Enums\JobApplicationStatus::Ghosted => 'bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-400 ring-1 ring-inset ring-gray-500/20 dark:ring-gray-400/20',
};
@endphp

<span class="inline-flex items-center gap-1.5 {{ $sizeClasses }} font-medium rounded-full {{ $colorClasses }}">
    <span class="w-1.5 h-1.5 rounded-full {{ str_replace('bg-', 'bg-', match ($status) {
        \App\Enums\JobApplicationStatus::Applied => 'bg-blue-500',
        \App\Enums\JobApplicationStatus::InReview => 'bg-indigo-500',
        \App\Enums\JobApplicationStatus::HrInterview => 'bg-purple-500',
        \App\Enums\JobApplicationStatus::TechnicalInterview => 'bg-orange-500',
        \App\Enums\JobApplicationStatus::FinalInterview => 'bg-pink-500',
        \App\Enums\JobApplicationStatus::Offer => 'bg-green-500',
        \App\Enums\JobApplicationStatus::Accepted => 'bg-emerald-500',
        \App\Enums\JobApplicationStatus::Rejected => 'bg-red-500',
        \App\Enums\JobApplicationStatus::Ghosted => 'bg-gray-500',
    }) }}"></span>
    {{ $status->label() }}
</span>

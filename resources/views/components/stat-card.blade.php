@props(['title' => '', 'value' => 0, 'icon' => '', 'color' => 'emerald'])

@php
$colorMap = [
    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'icon' => 'text-emerald-600 dark:text-emerald-400', 'count' => 'text-emerald-600 dark:text-emerald-400'],
    'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'icon' => 'text-blue-600 dark:text-blue-400', 'count' => 'text-blue-600 dark:text-blue-400'],
    'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'icon' => 'text-purple-600 dark:text-purple-400', 'count' => 'text-purple-600 dark:text-purple-400'],
    'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-900/20', 'icon' => 'text-orange-600 dark:text-orange-400', 'count' => 'text-orange-600 dark:text-orange-400'],
    'amber' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'icon' => 'text-amber-600 dark:text-amber-400', 'count' => 'text-amber-600 dark:text-amber-400'],
    'gray' => ['bg' => 'bg-gray-50 dark:bg-gray-800', 'icon' => 'text-gray-600 dark:text-gray-400', 'count' => 'text-gray-900 dark:text-gray-100'],
];
$colors = $colorMap[$color] ?? $colorMap['emerald'];
@endphp

<div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
    <div class="flex items-center gap-4">
        @if ($icon)
        <div class="w-12 h-12 {{ $colors['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 {{ $colors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
        @endif
        <div class="min-w-0">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ $title }}</p>
            <p class="mt-0.5 text-2xl font-bold text-gray-900 dark:text-white">{{ is_numeric($value) ? number_format($value) : $value }}</p>
        </div>
    </div>
</div>

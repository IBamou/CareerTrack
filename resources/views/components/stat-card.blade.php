@props(['title' => '', 'value' => 0, 'icon' => '', 'color' => 'indigo'])

@php
$colorMap = [
    'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'icon' => 'text-blue-600 dark:text-blue-400', 'count' => 'text-blue-600 dark:text-blue-400'],
    'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'icon' => 'text-indigo-600 dark:text-indigo-400', 'count' => 'text-indigo-600 dark:text-indigo-400'],
    'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'icon' => 'text-purple-600 dark:text-purple-400', 'count' => 'text-purple-600 dark:text-purple-400'],
    'green' => ['bg' => 'bg-green-50 dark:bg-green-900/20', 'icon' => 'text-green-600 dark:text-green-400', 'count' => 'text-green-600 dark:text-green-400'],
    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'icon' => 'text-emerald-600 dark:text-emerald-400', 'count' => 'text-emerald-600 dark:text-emerald-400'],
    'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-900/20', 'icon' => 'text-orange-600 dark:text-orange-400', 'count' => 'text-orange-600 dark:text-orange-400'],
    'gray' => ['bg' => 'bg-gray-50 dark:bg-gray-800', 'icon' => 'text-gray-600 dark:text-gray-400', 'count' => 'text-gray-900 dark:text-gray-100'],
];
$colors = $colorMap[$color] ?? $colorMap['indigo'];
@endphp

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 hover:shadow-sm transition-shadow">
    <div class="flex items-center gap-4">
        @if ($icon)
        <div class="w-11 h-11 {{ $colors['bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ $colors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

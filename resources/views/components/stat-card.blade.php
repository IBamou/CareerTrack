@props(['title' => '', 'value' => 0, 'icon' => '', 'color' => 'blue'])

@php
$colorMap = [
    'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-500/10', 'icon' => 'text-[#2563eb] dark:text-blue-400'],
    'yellow' => ['bg' => 'bg-yellow-50 dark:bg-yellow-500/10', 'icon' => 'text-yellow-500 dark:text-yellow-400'],
    'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-500/10', 'icon' => 'text-purple-500 dark:text-purple-400'],
    'green' => ['bg' => 'bg-green-50 dark:bg-green-500/10', 'icon' => 'text-green-500 dark:text-green-400'],
    'red' => ['bg' => 'bg-red-50 dark:bg-red-500/10', 'icon' => 'text-red-500 dark:text-red-400'],
    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'icon' => 'text-emerald-600 dark:text-emerald-400'],
    'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-500/10', 'icon' => 'text-orange-500 dark:text-orange-400'],
    'gray' => ['bg' => 'bg-slate-50 dark:bg-slate-700', 'icon' => 'text-slate-600 dark:text-slate-400'],
];
$colors = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-5 shadow-sm flex items-center gap-4">
    @if ($icon)
    <div class="w-12 h-12 rounded-full {{ $colors['bg'] }} flex items-center justify-center {{ $colors['icon'] }}">
        <i class="{{ $icon }} text-xl"></i>
    </div>
    @endif
    <div>
        <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ is_numeric($value) ? number_format($value) : $value }}</div>
        <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ $title }}</div>
    </div>
</div>

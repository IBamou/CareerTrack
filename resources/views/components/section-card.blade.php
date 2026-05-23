@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm']) }}>
    @if ($title)
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-6 flex-1 flex flex-col">
        {{ $slot }}
    </div>
</div>

@props(['title' => null, 'icon' => null])

<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden">
    @if ($title)
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                @if ($icon)
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $icon !!}
                        </svg>
                    </div>
                @endif
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
            </div>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>

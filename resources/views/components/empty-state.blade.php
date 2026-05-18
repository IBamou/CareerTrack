@props(['title', 'message', 'action' => null, 'actionLabel' => null])

<div class="text-center py-16 px-6">
    <div class="mx-auto w-20 h-20 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">{{ $message }}</p>
    @if ($action)
        <div class="mt-8">
            <a href="{{ $action }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-emerald-700 shadow-sm shadow-emerald-500/25 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ $actionLabel ?? 'Create' }}
            </a>
        </div>
    @endif
</div>

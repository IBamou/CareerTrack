@props(['title', 'message', 'action' => null, 'label' => null])

<div class="text-center py-16 px-6">
    <div class="mx-auto w-20 h-20 bg-blue-50 dark:bg-[#2563eb]/10 rounded-2xl flex items-center justify-center mb-6">
        <i class="fas fa-inbox text-3xl text-[#2563eb] dark:text-blue-400"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">{{ $message }}</p>
    @if ($action)
        <div class="mt-8">
            <a href="{{ $action }}" class="inline-flex items-center px-5 py-2.5 bg-[#2563eb] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 shadow-sm transition-colors">
                <i class="fas fa-plus mr-2"></i>
                {{ $label ?? 'Go' }}
            </a>
        </div>
    @endif
</div>

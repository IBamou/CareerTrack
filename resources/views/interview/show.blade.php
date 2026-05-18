<x-app-layout>
    <x-slot name="header">{{ $interview->type }}</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-5xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <a href="{{ route('interviews.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Interviews
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('interviews.edit', $interview) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Archive
                        </button>
                        <x-confirm-action-modal name="archive" title="Archive Interview?" message="This will move the interview to the archive. You can restore it later." :action="route('interviews.archive', $interview)" method="delete" button="Archive" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $interview->type }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $interview->jobApplication?->company?->name ?? 'No company' }}
                        &middot; {{ $interview->jobApplication?->job_title ?? 'No application' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-section-card title="Interview Details" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'>
                    <dl class="space-y-3">
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Scheduled At</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $interview->scheduled_at?->format('M d, Y g:i A') ?? 'Not set' }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Result</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $interview->result ?? 'Pending' }}</dd></div>
                    </dl>
                </x-section-card>

                <x-section-card title="Related Application" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'>
                    @if ($interview->jobApplication)
                        <a href="{{ route('job-applications.show', $interview->jobApplication) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $interview->jobApplication->job_title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $interview->jobApplication->company?->name }}</p>
                            </div>
                        </a>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No application linked</p>
                    @endif
                </x-section-card>
            </div>

            @if ($interview->notes)
                <x-section-card title="Notes" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $interview->notes }}</p>
                </x-section-card>
            @endif
        </div>
    </div>
</x-app-layout>

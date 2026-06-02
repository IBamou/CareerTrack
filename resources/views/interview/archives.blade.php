<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('interviews.index') }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Archived Interviews</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Restore or permanently delete past interviews</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">

        @if (session('status'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl text-sm font-medium text-blue-700 dark:text-blue-300">
                {{ session('status') }}
            </div>
        @endif

        @if ($interviews->isEmpty())
            <x-empty-state
                title="No archived interviews"
                message="Archived interviews will appear here."
                :action="route('interviews.index')"
                label="Go to Interviews"
            />
        @else
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($interviews as $interview)
                        <div class="p-4 sm:p-5 flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $interview->type?->label() }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $interview->scheduled_at?->format('M d, Y g:i A') }}
                                    &middot; {{ $interview->jobApplication?->job_title ?? 'No application' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <x-secondary-button type="button" @click="$dispatch('open-modal-restore_{{ $interview->id }}')">
                                    <i class="fas fa-undo text-xs mr-1"></i>
                                    Restore
                                </x-secondary-button>
                                <x-confirm-action-modal name="restore-{{ $interview->id }}" title="Restore Interview?" message="This will restore the interview from the archive." :action="route('interviews.restore', $interview)" method="post" button="Restore" />

                                <x-danger-button type="button" @click="$dispatch('open-modal-force_delete_{{ $interview->id }}')">
                                    <i class="fas fa-trash text-xs mr-1"></i>
                                    Delete
                                </x-danger-button>
                                <x-confirm-action-modal name="force-delete-{{ $interview->id }}" title="Delete Interview?" message="This will permanently delete this interview. This action cannot be undone." :action="route('interviews.forceDelete', $interview)" method="delete" button="Delete Forever" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-6">
                {{ $interviews->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

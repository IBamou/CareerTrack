<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('job-applications.index') }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Archived Applications') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Soft-deleted applications that can be restored or permanently removed</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <span class="text-sm font-medium text-amber-700 dark:text-amber-300">These applications are archived. You can restore or permanently delete them.</span>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ session('status') }}</span>
                </div>
            @endif

            @if ($jobApplications->isEmpty())
                <x-empty-state
                    title="No archived applications"
                    message="Archived applications will appear here."
                />
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($jobApplications as $application)
                            <div class="p-4 sm:p-5 opacity-75 hover:opacity-100 transition-opacity">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $application->job_title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                            {{ $application->company?->name ?? 'No company' }}
                                            &middot; Archived {{ $application->deleted_at?->diffForHumans() }}
                                        </p>
                                    </div>

                                    <div class="flex-shrink-0">
                                        <x-status-badge :status="$application->status" />
                                    </div>

                                    <div class="flex gap-2 flex-shrink-0">
                                        <form method="POST" action="{{ route('job-applications.restore', $application) }}">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Restore
                                            </button>
                                        </form>

                                        <div x-data="{ open: false }">
                                            <button type="button" @click="open = true" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-xs text-white hover:bg-red-700 transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>

                                            <x-confirm-action-modal
                                                name="force-delete-{{ $application->id }}"
                                                title="Permanently Delete?"
                                                message="This action cannot be undone. The application and all its data will be permanently removed."
                                                :action="route('job-applications.forceDelete', $application)"
                                                method="delete"
                                                button="Delete Forever"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    {{ $jobApplications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

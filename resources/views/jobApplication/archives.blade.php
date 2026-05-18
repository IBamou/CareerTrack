<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('job-applications.index') }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Archived Applications</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Restore or permanently delete past applications</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            @if ($jobApplications->isEmpty())
                <x-empty-state
                    title="No archived applications"
                    message="Archived applications will appear here."
                />
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($jobApplications as $application)
                            <div class="p-4 sm:p-5 opacity-75 hover:opacity-100 transition-opacity">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
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
                                            <x-secondary-button type="submit">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Restore
                                            </x-secondary-button>
                                        </form>

                                        <div x-data="{ open: false }">
                                            <x-danger-button @click="open = true">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </x-danger-button>
                                            <x-confirm-action-modal name="force-delete-{{ $application->id }}" title="Permanently Delete?" message="This action cannot be undone. The application and all its data will be permanently removed." :action="route('job-applications.forceDelete', $application)" method="delete" button="Delete Forever" />
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

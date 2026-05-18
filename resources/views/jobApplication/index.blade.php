<x-app-layout>
    <x-slot name="header">Applications</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Stats row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Total" :value="$stats['total']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>' color="indigo" />
                <x-stat-card title="Active" :value="$stats['active']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' color="blue" />
                <x-stat-card title="Interviews" :value="$stats['interviews']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>' color="purple" />
                <x-stat-card title="Offers" :value="$stats['offers']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>' color="emerald" />
            </div>

            <!-- Action bar -->
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Applications</h2>
                <a href="{{ route('job-applications.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Application
                </a>
            </div>

            @if ($jobApplications->isEmpty())
                <x-empty-state
                    title="No applications yet"
                    message="Start tracking your job applications to see them here."
                    :action="route('job-applications.create')"
                    action-label="Add Application"
                />
            @else
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($jobApplications as $app)
                            <div class="p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 text-white text-sm font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('job-applications.show', $app) }}" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 truncate block">{{ $app->job_title }}</a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                            {{ $app->company?->name ?? 'No company' }}
                                            @if ($app->location_city)
                                                &middot; {{ $app->location_city }}
                                            @endif
                                            &middot; {{ $app->created_at?->diffForHumans() }}
                                        </p>
                                    </div>

                                    <x-status-badge :status="$app->status" />

                                    <div class="flex items-center gap-1 flex-shrink-0" x-data="{ open: false }">
                                        <button @click="open = !open" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                        </button>
                                        <div x-show="open" @click.outside="open = false" class="absolute right-0 z-50 mt-8 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black/5 dark:ring-white/10 py-1" style="display: none;">
                                            <a href="{{ route('job-applications.show', $app) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50">View</a>
                                            <a href="{{ route('job-applications.edit', $app) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50">Edit</a>
                                            <form method="POST" action="{{ route('job-applications.archive', $app) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">Archive</button>
                                            </form>
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

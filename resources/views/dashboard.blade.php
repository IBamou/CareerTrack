<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Here's an overview of your job search progress.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Total Applications" :value="$stats['applications']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>' color="emerald" />
                <x-stat-card title="Companies" :value="$stats['companies']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>' color="blue" />
                <x-stat-card title="Active" :value="$stats['active']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' color="purple" />
                <x-stat-card title="Interviews" :value="$stats['interviews']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>' color="amber" />
            </div>

            <x-section-card title="Recent Applications" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'>
                @if ($recentApplications->isEmpty())
                    <x-empty-state
                        title="No applications yet"
                        message="Start tracking your job applications to see them here."
                        :action="route('job-applications.create')"
                        action-label="Add Application"
                    />
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($recentApplications as $app)
                            <a href="{{ route('job-applications.show', $app) }}" class="flex items-center gap-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 -mx-4 px-4 transition-colors">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $app->job_title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $app->company?->name ?? 'No company' }}</p>
                                </div>
                                <x-status-badge :status="$app->status" size="sm" />
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('job-applications.index') }}" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">View all applications &rarr;</a>
                    </div>
                @endif
            </x-section-card>
        </div>
    </div>
</x-app-layout>

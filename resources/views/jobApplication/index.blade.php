<x-app-layout>
    <x-slot name="header">Applications</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Stats row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Total" :value="$stats['total']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>' color="emerald" />
                <x-stat-card title="Active" :value="$stats['active']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' color="blue" />
                <x-stat-card title="Interviews" :value="$stats['interviews']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>' color="purple" />
                <x-stat-card title="Offers" :value="$stats['offers']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>' color="amber" />
            </div>

            <!-- Action bar -->
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Applications</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('job-applications.kanban') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        Kanban
                    </a>
                    <a href="{{ route('job-applications.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Application
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('job-applications.index') }}" class="flex flex-wrap items-center gap-3">
                <select name="status" class="w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-xl shadow-sm text-sm" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    @foreach (\App\Enums\JobApplicationStatus::cases() as $s)
                        <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <select name="priority" class="w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-xl shadow-sm text-sm" onchange="this.form.submit()">
                    <option value="">All priorities</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                </select>
                @if (request()->anyFilled(['status', 'priority']))
                    <a href="{{ route('job-applications.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 underline">Clear</a>
                @endif
            </form>

            @if ($jobApplications->isEmpty())
                <x-empty-state
                    title="No applications yet"
                    message="Start tracking your job applications to see them here."
                    :action="route('job-applications.create')"
                    action-label="Add Application"
                />
            @else
                <form method="POST" action="{{ route('job-applications.bulk-action') }}" x-data="{ selected: [], selectAll: false }">
                    @csrf
                    <input type="hidden" name="bulk_action" value="">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($jobApplications as $app)
                                <div class="p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <input type="checkbox" name="selected[]" value="{{ $app->id }}" x-model="selected" class="rounded border-gray-300 dark:border-gray-600 text-emerald-600 focus:ring-emerald-500">

                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                            {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('job-applications.show', $app) }}" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 truncate block">{{ $app->job_title }}</a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                                {{ $app->company?->name ?? 'No company' }}
                                                @if ($app->location_city)
                                                    &middot; {{ $app->location_city }}
                                                @endif
                                                &middot; {{ $app->created_at?->diffForHumans() }}
                                            </p>
                                        </div>

                                        <x-status-badge :status="$app->status" />

                                        @php
                                            $priorityClasses = [
                                                'low' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                                                'normal' => 'bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300',
                                                'high' => 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize {{ $priorityClasses[$app->priority] ?? $priorityClasses['normal'] }}">{{ $app->priority }}</span>

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

                    <!-- Bulk actions bar -->
                    <div x-show="selected.length > 0" x-cloak class="fixed bottom-0 left-0 right-0 z-50 p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-2xl">
                        <div class="max-w-7xl mx-auto flex items-center justify-between">
                            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selected.length + ' selected'"></p>
                            <div class="flex items-center gap-3">
                                <select name="bulk_status" class="border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-lg text-sm" @change="if($event.target.value) { $event.target.form.querySelector('input[name=bulk_action]').value = 'change_status'; $event.target.form.submit() }">
                                    <option value="">Change Status…</option>
                                    @foreach (\App\Enums\JobApplicationStatus::cases() as $s)
                                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" @click="document.querySelector('input[name=bulk_action]').value = 'archive'" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Archive Selected
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif

            <style>[x-cloak] { display: none !important; }</style>
        </div>
    </div>
</x-app-layout>

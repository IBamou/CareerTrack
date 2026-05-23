<x-app-layout>
    <x-slot name="header">Job Applications</x-slot>

    <div class="max-w-7xl mx-auto">

        @if (session('status'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl text-sm font-medium text-blue-700 dark:text-blue-300 flex items-center gap-2">
                <i class="fas fa-check-circle text-blue-500"></i>
                {{ session('status') }}
            </div>
        @endif

        <header class="flex-shrink-0 flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Job Applications</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Manage and track your active job opportunities.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-1 flex">
                    <a href="{{ route('job-applications.index') }}" class="px-4 py-1.5 rounded-md bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white font-medium flex items-center gap-2 text-sm">
                        <i class="fas fa-list text-xs"></i> List
                    </a>
                    <a href="{{ route('job-applications.kanban') }}" class="px-4 py-1.5 rounded-md text-slate-500 dark:text-slate-400 font-medium hover:text-slate-800 dark:hover:text-white transition flex items-center gap-2 text-sm">
                        <i class="fas fa-columns text-xs"></i> Board
                    </a>
                </div>
                <a href="{{ route('job-applications.create') }}" class="bg-[#2563eb] text-white px-4 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2 text-sm shadow-sm">
                    <i class="fas fa-plus"></i> Add Application
                </a>
            </div>
        </header>

        <form method="GET" action="{{ route('job-applications.index') }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-72">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="q" placeholder="Search title, company..." value="{{ request('q') }}" class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm">
                </div>
                <div class="flex gap-2">
                    <select name="status" class="border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 rounded-lg text-sm px-3 py-2 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none" onchange="this.form.submit()">
                        <option value="">All statuses</option>
                        @foreach (\App\Enums\JobApplicationStatus::cases() as $s)
                            <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
                        @endforeach
                    </select>
                    <select name="priority" class="border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 rounded-lg text-sm px-3 py-2 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none" onchange="this.form.submit()">
                        <option value="">All priorities</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @if (request()->anyFilled(['status', 'priority', 'q']))
                        <a href="{{ route('job-applications.index') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 underline self-center">Clear</a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-6 text-sm text-slate-500 dark:text-slate-400">
                <div>Active: <span class="font-semibold text-slate-900 dark:text-white">{{ $stats['active'] }}</span></div>
                <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
                <div>Interviews: <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $stats['interviews'] }}</span></div>
                <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
                <div>Total: <span class="font-semibold text-slate-900 dark:text-white">{{ $stats['total'] }}</span></div>
            </div>
        </form>

        @if ($jobApplications->isEmpty())
            <x-empty-state
                title="No applications yet"
                message="Start tracking your job applications to see them here."
                :action="route('job-applications.create')"
                label="Add Application"
            />
        @else
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                            <th class="p-4 pl-6 w-10">
                                <input type="checkbox" class="rounded border-slate-300 dark:border-slate-600 text-[#2563eb] focus:ring-[#2563eb]">
                            </th>
                            <th class="p-4">Company & Position</th>
                            <th class="p-4">Stage</th>
                            <th class="p-4">Date Applied</th>
                            <th class="p-4">Next Event</th>
                            <th class="p-4 text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                        @foreach ($jobApplications as $app)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition">
                                <td class="p-4 pl-6">
                                    <input type="checkbox" class="rounded border-slate-300 dark:border-slate-600 text-[#2563eb] focus:ring-[#2563eb]">
                                </td>
                                <td class="p-4">
                                    <a href="{{ route('job-applications.show', $app) }}" class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-white dark:bg-slate-700 border border-slate-100 dark:border-slate-600 shadow-sm flex items-center justify-center flex-shrink-0 font-bold text-sm" style="color: {{ $app->company?->color ?? '#64748b' }}">
                                            {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $app->job_title }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">{{ $app->company?->name ?? 'No company' }}</div>
                                        </div>
                                    </a>
                                </td>
                                <td class="p-4">
                                    <x-status-badge :status="$app->status" />
                                </td>
                                <td class="p-4 text-slate-500 dark:text-slate-400 font-medium">{{ $app->created_at?->format('M d, Y') }}</td>
                                <td class="p-4">
                                    @php
                                        $nextEvent = $app->interviews->where('scheduled_at', '>=', now())->sortBy('scheduled_at')->first();
                                    @endphp
                                    @if ($nextEvent)
                                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                                            <i class="far fa-calendar-alt text-[#2563eb]"></i> {{ $nextEvent->type }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 italic">No upcoming events</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right pr-6">
                                    <a href="{{ route('job-applications.edit', $app) }}" class="text-slate-400 hover:text-[#2563eb] p-1 inline-block"><i class="fas fa-pen"></i></a>
                                    <button type="button" @click="$dispatch('open-modal-archive_{{ $app->id }}')" class="text-slate-400 hover:text-red-500 p-1 ml-2 inline-block"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>

                            <x-confirm-action-modal name="archive-{{ $app->id }}" title="Archive Application?" message="This will move the application to the archive. You can restore it later." :action="route('job-applications.archive', $app)" method="delete" button="Archive" />
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between text-sm text-slate-500 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-800/50">
                    <div>
                        Showing <span class="font-medium text-slate-900 dark:text-white">{{ $jobApplications->firstItem() ?? 0 }}</span> to <span class="font-medium text-slate-900 dark:text-white">{{ $jobApplications->lastItem() ?? 0 }}</span> of <span class="font-medium text-slate-900 dark:text-white">{{ $jobApplications->total() }}</span> applications
                    </div>
                    <div class="flex gap-1">
                        @if ($jobApplications->onFirstPage())
                            <button disabled class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-medium text-sm opacity-50 cursor-not-allowed">Previous</button>
                        @else
                            <a href="{{ $jobApplications->previousPageUrl() }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-slate-600 transition text-sm">Previous</a>
                        @endif
                        @if ($jobApplications->hasMorePages())
                            <a href="{{ $jobApplications->nextPageUrl() }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-slate-600 transition text-sm">Next</a>
                        @else
                            <button disabled class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-medium text-sm opacity-50 cursor-not-allowed">Next</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
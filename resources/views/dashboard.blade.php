<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Here's an overview of your job search progress.</p>
                </div>
            </div>

            <!-- Quick Add Form -->
            <form method="POST" action="{{ route('dashboard.quick-add') }}" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 shadow-sm">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 min-w-0">
                        <input type="text" name="job_title" placeholder="Job title (e.g. Frontend Developer)" required class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 focus:border-transparent dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <input type="text" name="company_name" placeholder="Company name" required class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 focus:border-transparent dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500" />
                    </div>
                    <div class="w-full sm:w-auto">
                        <select name="status" class="w-full sm:w-auto px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 focus:border-transparent dark:text-gray-300">
                            @foreach (\App\Enums\JobApplicationStatus::cases() as $s)
                                <option value="{{ $s->value }}" {{ $s === \App\Enums\JobApplicationStatus::Applied ? 'selected' : '' }}>{{ $s->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add
                        </button>
                    </div>
                </div>
            </form>

            @if ($stats['applications'] === 0)
                <!-- Getting Started -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800/50 p-6">
                    <h3 class="text-lg font-semibold text-emerald-900 dark:text-emerald-200 mb-2">Getting Started</h3>
                    <p class="text-sm text-emerald-700 dark:text-emerald-300 mb-4">Start by logging your first job application above. Then track your progress as you go through the hiring process.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="flex items-start gap-3 p-3 bg-white/70 dark:bg-gray-800/50 rounded-xl">
                            <div class="flex items-center justify-center w-8 h-8 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400 text-sm font-bold">1</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Log an application</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Just the job title and company</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-white/70 dark:bg-gray-800/50 rounded-xl">
                            <div class="flex items-center justify-center w-8 h-8 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400 text-sm font-bold">2</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Update status</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Click steps on the timeline</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-white/70 dark:bg-gray-800/50 rounded-xl">
                            <div class="flex items-center justify-center w-8 h-8 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400 text-sm font-bold">3</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Add interviews</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Track your interview schedule</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-stat-card title="Total Applications" :value="$stats['applications']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>' color="emerald" />
                    <x-stat-card title="Companies" :value="$stats['companies']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>' color="blue" />
                    <x-stat-card title="Active" :value="$stats['active']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' color="purple" />
                    <x-stat-card title="Interviews" :value="$stats['interviews']" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>' color="amber" />
                </div>
            @endif

            <x-section-card title="Recent Applications" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'>
                @if ($recentApplications->isEmpty())
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Your recent applications will appear here.</p>
                    </div>
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

            @if (isset($monthly) && $monthly->isNotEmpty())
            @php
                $max = $monthly->max('count');
                $total = $monthly->sum('count');
                $avg = round($total / $monthly->count(), 1);
                $trend = $monthly->count() >= 2 ? $monthly->last()->count - $monthly->take(-2)->first()->count : 0;
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Applications per Month</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $total }} total &middot; {{ $avg }} avg/month</p>
                    </div>
                    @if ($trend != 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full {{ $trend > 0 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $trend > 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"/></svg>
                            {{ $trend > 0 ? '+' : '' }}{{ $trend }} from last month
                        </span>
                    @endif
                </div>
                <div class="flex items-end gap-3 h-48">
                    @foreach ($monthly as $m)
                        @php
                            $pct = $max > 0 ? ($m->count / $max) * 100 : 0;
                            $isHighest = $m->count === $max && $max > 0;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-2 group">
                            <span class="text-xs font-semibold text-gray-900 dark:text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200">{{ $m->count }}</span>
                            <div class="w-full rounded-t-lg relative transition-all duration-500 ease-out group-hover:shadow-lg group-hover:shadow-emerald-500/20" style="height: {{ $pct }}%">
                                <div class="absolute inset-0 rounded-t-lg bg-gradient-to-t from-emerald-500 to-emerald-400 {{ $isHighest ? 'ring-2 ring-emerald-300 dark:ring-emerald-600' : '' }} group-hover:from-emerald-400 group-hover:to-emerald-300 transition-all duration-300"></div>
                                <div class="absolute inset-x-0 top-0 h-1/2 rounded-t-lg bg-white/20"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::create()->month($m->month)->format('M') }}@isset($m->year)<span class="text-gray-400 dark:text-gray-500"> {{ $m->year }}</span>@endisset</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($statusDistribution) && $statusDistribution->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Distribution</h3>
                <div class="space-y-3">
                    @php $total = $statusDistribution->sum(); @endphp
                    @foreach ($statusDistribution as $status => $count)
                        @php $pct = $total > 0 ? round(($count / $total) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $count }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-emerald-500 rounded-full h-2 transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

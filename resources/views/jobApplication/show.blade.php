<x-app-layout>
    <x-slot name="header">{{ $jobApplication->job_title }}</x-slot>

    @if (session('status'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>
            {{ session('status') }}
        </div>
    @endif

    <nav class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 mb-4">
        <a class="hover:text-[#2563eb] dark:hover:text-blue-400 transition-colors font-medium" href="{{ route('job-applications.index') }}">Applications</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-900 dark:text-white font-semibold">{{ $jobApplication->job_title }}</span>
    </nav>

    <div class="flex items-start justify-between gap-4 mb-6">
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white truncate">{{ $jobApplication->job_title }}</h2>
                    <p class="text-base font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ $jobApplication->company?->name ?? 'No company' }}
                        @if ($jobApplication->location_city)
                            <span class="mx-1.5">&middot;</span> {{ $jobApplication->location_city }}
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('job-applications.edit', $jobApplication) }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center gap-1.5">
                        <i class="fas fa-edit text-xs"></i>
                        Edit
                    </a>
                    <div>
                        <button type="button" @click="$dispatch('open-modal-archive')" class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 text-red-500 rounded-lg text-sm font-medium hover:bg-red-50 dark:hover:bg-red-900/20 transition-all flex items-center gap-1.5">
                            <i class="fas fa-archive text-xs"></i>
                            Archive
                        </button>
                        <x-confirm-action-modal name="archive" title="Archive Application?" message="This will move the application to the archive. You can restore it later." :action="route('job-applications.archive', $jobApplication)" method="delete" button="Archive" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-3 flex-wrap">
                @php
                    $statusColors = [
                        'applied' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'text' => 'text-blue-600 dark:text-blue-400'],
                        'in_review' => ['bg' => 'bg-orange-50 dark:bg-orange-900/20', 'text' => 'text-orange-600 dark:text-orange-400'],
                        'hr_interview' => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'text' => 'text-purple-600 dark:text-purple-400'],
                        'technical_interview' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'text' => 'text-indigo-600 dark:text-indigo-400'],
                        'final_interview' => ['bg' => 'bg-violet-50 dark:bg-violet-900/20', 'text' => 'text-violet-600 dark:text-violet-400'],
                        'offer' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'text' => 'text-amber-600 dark:text-amber-400'],
                        'accepted' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                        'rejected' => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-600 dark:text-red-400'],
                        'ghosted' => ['bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-500 dark:text-slate-400'],
                    ];
                    $statusLabels = [
                        'applied' => 'Applied', 'in_review' => 'In Review', 'hr_interview' => 'HR Interview',
                        'technical_interview' => 'Technical Interview', 'final_interview' => 'Final Interview',
                        'offer' => 'Offer', 'accepted' => 'Accepted', 'rejected' => 'Rejected', 'ghosted' => 'Ghosted',
                    ];
                    $sc = $statusColors[$jobApplication->status->value] ?? $statusColors['applied'];
                    $priorityColors = [
                        'low' => ['bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-500 dark:text-slate-400'],
                        'normal' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'text' => 'text-amber-600 dark:text-amber-400'],
                        'high' => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-600 dark:text-red-400'],
                    ];
                    $pc = $priorityColors[$jobApplication->priority] ?? $priorityColors['normal'];
                @endphp
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium {{ $sc['bg'] }} {{ $sc['text'] }}">
                    <i class="fas fa-circle text-[6px]"></i>
                    {{ $statusLabels[$jobApplication->status->value] ?? $jobApplication->status->label() }}
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium {{ $pc['bg'] }} {{ $pc['text'] }}">
                    <i class="fas fa-flag text-[10px]"></i>
                    {{ ucfirst($jobApplication->priority) }}
                </span>
                @if ($jobApplication->salary_min || $jobApplication->salary_max)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 dark:bg-blue-900/20 text-[#2563eb] dark:text-blue-400">
                        <i class="fas fa-dollar-sign text-[10px]"></i>
                        {{ $jobApplication->salary_min ? number_format($jobApplication->salary_min) : '' }}{{ $jobApplication->salary_min && $jobApplication->salary_max ? ' - ' : '' }}{{ $jobApplication->salary_max ? number_format($jobApplication->salary_max) : '' }} {{ $jobApplication->currency }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:shadow-md hover:border-[#2563eb]/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-[#2563eb]"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Applied</span>
            </div>
            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $jobApplication->applied_at?->format('M d, Y') ?? 'Not set' }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:shadow-md hover:border-[#2563eb]/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-[#2563eb]"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Location</span>
            </div>
            <div class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $jobApplication->location_city ?? ($jobApplication->location_type?->label() ?? 'Not set') }}</div>
        </div>
        @if ($jobApplication->salary_min || $jobApplication->salary_max)
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:shadow-md hover:border-[#2563eb]/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-emerald-600"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Salary</span>
            </div>
            <div class="text-sm font-bold text-slate-900 dark:text-white">
                {{ $jobApplication->salary_min ? number_format($jobApplication->salary_min) : '' }}{{ $jobApplication->salary_min && $jobApplication->salary_max ? ' - ' : '' }}{{ $jobApplication->salary_max ? number_format($jobApplication->salary_max) : '' }} {{ $jobApplication->currency }}
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:shadow-md hover:border-[#2563eb]/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                    <i class="fas fa-briefcase text-[#2563eb]"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Type</span>
            </div>
            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $jobApplication->location_type?->label() ?? 'Not specified' }}</div>
        </div>
        @else
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:shadow-md hover:border-[#2563eb]/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                    <i class="fas fa-briefcase text-[#2563eb]"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Type</span>
            </div>
            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $jobApplication->location_type?->label() ?? 'Not specified' }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:shadow-md hover:border-[#2563eb]/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg {{ $pc['bg'] }} flex items-center justify-center">
                    <i class="fas fa-flag {{ $pc['text'] }}"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Priority</span>
            </div>
            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ ucfirst($jobApplication->priority) }}</div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 space-y-5">

            <section class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Application Progress</h3>
                </div>
                <div class="p-5">
                    @php
                        $statuses = [
                            ['key' => 'applied', 'label' => 'Applied', 'icon' => 'fa-check-circle'],
                            ['key' => 'in_review', 'label' => 'In Review', 'icon' => 'fa-eye'],
                            ['key' => 'hr_interview', 'label' => 'HR Interview', 'icon' => 'fa-users'],
                            ['key' => 'technical_interview', 'label' => 'Technical Interview', 'icon' => 'fa-code'],
                            ['key' => 'final_interview', 'label' => 'Final Interview', 'icon' => 'fa-check-double'],
                            ['key' => 'offer', 'label' => 'Offer', 'icon' => 'fa-gem'],
                            ['key' => 'accepted', 'label' => 'Accepted', 'icon' => 'fa-thumbs-up'],
                            ['key' => 'rejected', 'label' => 'Rejected', 'icon' => 'fa-times-circle'],
                            ['key' => 'ghosted', 'label' => 'Ghosted', 'icon' => 'fa-user-slash'],
                        ];
                        $statusKeys = ['applied', 'in_review', 'hr_interview', 'technical_interview', 'final_interview', 'offer', 'accepted', 'rejected', 'ghosted'];
                        $currentKey = $jobApplication->status->value;
                        $currentIndex = array_search($currentKey, $statusKeys);
                        $isTerminal = in_array($currentKey, ['rejected', 'ghosted']);
                    @endphp
                    <div class="space-y-0">
                        @foreach ($statuses as $index => $step)
                            @php
                                $isActive = $currentKey === $step['key'];
                                $isPast = !$isActive && !$isTerminal && $index <= $currentIndex;
                                $canUpdate = !$isActive;
                            @endphp
                            @if ($canUpdate)
                                <form method="POST" action="{{ route('job-applications.updateStatus', $jobApplication) }}" class="group">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $step['key'] }}">
                                    <button type="submit" class="w-full text-left flex items-center gap-4 cursor-pointer py-2.5 px-3 -mx-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all">
                            @else
                                <div class="flex items-center gap-4 py-2.5">
                            @endif
                                <div class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 flex-shrink-0 {{ $isActive ? 'bg-blue-50 dark:bg-blue-500/10 text-[#2563eb] ring-2 ring-[#2563eb]/30' : ($isPast ? 'bg-blue-50 dark:bg-blue-500/10 text-[#2563eb]' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500') }} {{ $canUpdate ? 'group-hover:ring-2 group-hover:ring-[#2563eb]/50' : '' }}">
                                    @if ($isPast && !$isActive)
                                        <i class="fas fa-check text-sm"></i>
                                    @else
                                        <i class="fas {{ $step['icon'] }} text-sm"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium transition-colors duration-200 {{ $isActive ? 'text-[#2563eb]' : ($isPast ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-500') }} {{ $canUpdate ? 'group-hover:text-[#2563eb]' : '' }}">{{ $step['label'] }}</p>
                                    @if ($isActive)
                                        <p class="text-xs text-[#2563eb]/70 font-medium mt-0.5">Current status</p>
                                    @elseif ($canUpdate)
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">Click to update</p>
                                    @endif
                                </div>
                            @if ($canUpdate)
                                    </button>
                                </form>
                            @else
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>

            @if ($jobApplication->notes)
                <section class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Notes</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line">{{ $jobApplication->notes }}</p>
                    </div>
                </section>
            @endif

            @if ($jobApplication->activities->isNotEmpty())
                <section class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Activity Log</h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach ($jobApplication->activities->sortByDesc('created_at')->take(10) as $log)
                            <div class="px-4 py-3 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-history text-slate-400 dark:text-slate-500 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $log->description ?? $log->action }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div class="space-y-5">

            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Company</h4>
                    @if ($jobApplication->company)
                        <a href="{{ route('companies.show', $jobApplication->company) }}" class="text-xs font-medium text-[#2563eb] dark:text-blue-400 hover:underline">View &rarr;</a>
                    @endif
                </div>
                @if ($jobApplication->company)
                    @php $company = $jobApplication->company; @endphp
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-[#2563eb] to-blue-400 text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $company->name }}</p>
                                @if ($company->industry)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $company->industry }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2">
                            @if ($company->location)
                                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                    <i class="fas fa-map-marker-alt text-slate-400"></i>
                                    <span>{{ $company->location }}</span>
                                </div>
                            @endif
                            @if ($company->website)
                                <div class="flex items-center gap-2 text-xs">
                                    <i class="fas fa-globe text-slate-400"></i>
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-[#2563eb] dark:text-blue-400 hover:underline truncate">{{ parse_url($company->website, PHP_URL_HOST) ?: $company->website }}</a>
                                </div>
                            @endif
                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <i class="fas fa-briefcase text-slate-400"></i>
                                <span>{{ $company->job_applications_count }} application{{ $company->job_applications_count !== 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        @if ($company->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-slate-100 dark:border-slate-700">
                                @foreach ($company->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full" style="background-color: {{ $tag->color ?? '#0891b2' }}15; color: {{ $tag->color ?? '#0891b2' }}">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <i class="fas fa-building text-slate-300 dark:text-slate-600 text-3xl mb-2"></i>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">No company linked</p>
                        <a href="{{ route('job-applications.edit', $jobApplication) }}" class="px-3 py-1.5 bg-[#2563eb] text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition-all inline-flex items-center gap-1">
                            <i class="fas fa-plus text-xs"></i>
                            Link Company
                        </a>
                    </div>
                @endif
            </div>

            @if ($jobApplication->company && $jobApplication->company->contacts->isNotEmpty())
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Contacts</h4>
                    <span class="bg-blue-50 dark:bg-blue-500/10 text-[#2563eb] text-xs font-medium px-2 py-0.5 rounded-full">{{ $jobApplication->company->contacts->count() }}</span>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($jobApplication->company->contacts as $contact)
                        <a href="{{ route('contacts.show', $contact) }}" class="px-4 py-3 flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-[#2563eb] to-blue-400 text-white text-xs font-semibold flex-shrink-0">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 dark:text-white group-hover:text-[#2563eb] transition-colors truncate">{{ $contact->name }}</p>
                                @if ($contact->role || $contact->email)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $contact->role ?? $contact->email }}</p>
                                @endif
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-[#2563eb] transition-colors text-xs"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if (!empty($jobApplication->links))
                <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Links</h4>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @if (!empty($jobApplication->links['job_posting']))
                            <a href="{{ $jobApplication->links['job_posting'] }}" target="_blank" rel="noopener noreferrer" class="px-4 py-3 flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 dark:group-hover:bg-blue-500/20 transition-colors">
                                    <i class="fas fa-external-link-alt text-[#2563eb]/60 group-hover:text-[#2563eb] text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white group-hover:text-[#2563eb] transition-colors">Job Posting</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $jobApplication->links['job_posting'] }}</p>
                                </div>
                            </a>
                        @endif
                        @if (!empty($jobApplication->links['company_website']))
                            <a href="{{ $jobApplication->links['company_website'] }}" target="_blank" rel="noopener noreferrer" class="px-4 py-3 flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 dark:group-hover:bg-blue-500/20 transition-colors">
                                    <i class="fas fa-globe text-[#2563eb]/60 group-hover:text-[#2563eb] text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white group-hover:text-[#2563eb] transition-colors">Company Website</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $jobApplication->links['company_website'] }}</p>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Interviews</h4>
                    <span class="bg-blue-50 dark:bg-blue-500/10 text-[#2563eb] text-xs font-medium px-2 py-0.5 rounded-full">{{ $jobApplication->interviews->count() }}</span>
                </div>
                @if ($jobApplication->interviews->isEmpty())
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <i class="fas fa-calendar text-slate-300 dark:text-slate-600 text-3xl mb-2"></i>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">No interviews yet</p>
                        <a href="{{ route('interviews.create', ['job_application_id' => $jobApplication->id]) }}" class="px-3 py-1.5 bg-[#2563eb] text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition-all inline-flex items-center gap-1">
                            <i class="fas fa-plus text-xs"></i>
                            Add Interview
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach ($jobApplication->interviews->sortByDesc('scheduled_at') as $interview)
                            <a href="{{ route('interviews.show', $interview) }}" class="px-4 py-3 flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-50 dark:group-hover:bg-blue-500/10 transition-colors">
                                    <i class="fas fa-calendar text-slate-400 dark:text-slate-500 group-hover:text-[#2563eb] text-sm transition-colors"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white group-hover:text-[#2563eb] transition-colors truncate">{{ $interview->type }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $interview->scheduled_at?->format('M d, Y g:i A') }}</p>
                                    @if ($interview->result)
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $interview->result }}</p>
                                    @endif
                                </div>
                                <i class="fas fa-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-[#2563eb] transition-colors text-xs"></i>
                            </a>
                        @endforeach
                    </div>
                    <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('interviews.create', ['job_application_id' => $jobApplication->id]) }}" class="text-xs text-[#2563eb] dark:text-blue-400 font-medium hover:underline inline-flex items-center gap-1">
                            <i class="fas fa-plus text-xs"></i>
                            Add Interview
                        </a>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Documents</h4>
                </div>
                @if ($jobApplication->documents->isEmpty())
                    <div class="flex flex-col items-center justify-center py-4 text-center">
                        <i class="fas fa-folder-open text-slate-300 dark:text-slate-600 text-3xl mb-1"></i>
                        <p class="text-xs text-slate-500 dark:text-slate-400">No documents yet</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach ($jobApplication->documents as $doc)
                            <div class="px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file text-slate-400 dark:text-slate-500 text-sm"></i>
                                    </div>
                                    <a href="{{ route('documents.download', $doc) }}" class="text-sm font-medium text-slate-900 dark:text-white hover:text-[#2563eb] truncate">{{ $doc->name }}</a>
                                </div>
                                <div>
                                    <button type="button" @click="$dispatch('open-modal-delete_doc_{{ $doc->id }}')" class="text-xs text-red-500 font-medium hover:underline">Delete</button>
                                    <x-confirm-action-modal name="delete-doc-{{ $doc->id }}" title="Delete Document?" message="Are you sure you want to delete this document?" :action="route('documents.destroy', $doc)" method="delete" button="Delete" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700">
                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="documentable_type" value="App\Models\JobApplication">
                        <input type="hidden" name="documentable_id" value="{{ $jobApplication->id }}">
                        <input type="file" name="file" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 dark:file:bg-blue-500/10 file:text-[#2563eb] dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-500/20 transition-colors cursor-pointer">
                        <button type="submit" class="text-xs text-[#2563eb] dark:text-blue-400 font-medium hover:underline flex-shrink-0">Upload</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
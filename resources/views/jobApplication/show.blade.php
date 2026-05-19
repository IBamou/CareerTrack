<x-app-layout>
    <x-slot name="header">{{ $jobApplication->job_title }}</x-slot>

    @if (session('status'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mb-4">
        <a class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors font-medium" href="{{ route('job-applications.index') }}">Applications</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-semibold">{{ $jobApplication->job_title }}</span>
    </nav>

    <!-- Hero header -->
    <div class="flex items-start justify-between gap-4 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-primary to-primary-container text-white text-xl font-bold shadow-sm flex-shrink-0">
                {{ strtoupper(substr($jobApplication->company?->name ?? $jobApplication->job_title, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-display-lg text-on-surface">{{ $jobApplication->job_title }}</h2>
                <div class="flex items-center gap-2 mt-2 flex-wrap">
                    @php
                        $statusColors = [
                            'applied' => ['bg' => 'bg-primary/10', 'text' => 'text-primary'],
                            'in_review' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'text' => 'text-blue-600 dark:text-blue-400'],
                            'hr_interview' => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'text' => 'text-purple-600 dark:text-purple-400'],
                            'technical_interview' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'text' => 'text-indigo-600 dark:text-indigo-400'],
                            'final_interview' => ['bg' => 'bg-violet-50 dark:bg-violet-900/20', 'text' => 'text-violet-600 dark:text-violet-400'],
                            'offer' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'text' => 'text-amber-600 dark:text-amber-400'],
                            'accepted' => ['bg' => 'bg-secondary/10', 'text' => 'text-secondary'],
                            'rejected' => ['bg' => 'bg-error/5', 'text' => 'text-error'],
                            'ghosted' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-500 dark:text-gray-400'],
                        ];
                        $statusLabels = [
                            'applied' => 'Applied', 'in_review' => 'In Review', 'hr_interview' => 'HR Interview',
                            'technical_interview' => 'Technical Interview', 'final_interview' => 'Final Interview',
                            'offer' => 'Offer', 'accepted' => 'Accepted', 'rejected' => 'Rejected', 'ghosted' => 'Ghosted',
                        ];
                        $sc = $statusColors[$jobApplication->status->value] ?? $statusColors['applied'];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[11px] font-medium {{ $sc['bg'] }} {{ $sc['text'] }}">
                        {{ $statusLabels[$jobApplication->status->value] ?? $jobApplication->status->label() }}
                    </span>
                    @php
                        $priorityColors = [
                            'low' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-500 dark:text-gray-400'],
                            'normal' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'text' => 'text-amber-600 dark:text-amber-400'],
                            'high' => ['bg' => 'bg-error/5', 'text' => 'text-error'],
                        ];
                        $pc = $priorityColors[$jobApplication->priority] ?? $priorityColors['normal'];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[11px] font-medium {{ $pc['bg'] }} {{ $pc['text'] }}">
                        {{ ucfirst($jobApplication->priority) }} Priority
                    </span>
                    @if ($jobApplication->salary_min || $jobApplication->salary_max)
                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-primary/10 text-primary">
                            {{ $jobApplication->salary_min ? number_format($jobApplication->salary_min) : '' }}{{ $jobApplication->salary_min && $jobApplication->salary_max ? ' - ' : '' }}{{ $jobApplication->salary_max ? number_format($jobApplication->salary_max) : '' }} {{ $jobApplication->currency }}
                        </span>
                    @endif
                </div>
                <p class="text-[13px] text-on-surface-variant/60 mt-1">
                    {{ $jobApplication->company?->name ?? 'No company' }}
                    @if ($jobApplication->location_city)
                        <span class="mx-1">&middot;</span> {{ $jobApplication->location_city }}
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('job-applications.edit', $jobApplication) }}" class="px-3 py-1.5 border border-outline-variant text-on-surface-variant rounded-lg text-[13px] font-medium hover:bg-surface-container transition-all flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">edit</span>
                Edit
            </a>
            <div x-data="{ open: false }">
                <button @click="open = true" class="px-3 py-1.5 border border-outline-variant text-error rounded-lg text-[13px] font-medium hover:bg-error/5 transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">archive</span>
                    Archive
                </button>
                <x-confirm-action-modal name="archive" title="Archive Application?" message="This will move the application to the archive. You can restore it later." :action="route('job-applications.archive', $jobApplication)" method="delete" button="Archive" />
            </div>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-outline-variant/50 rounded-xl p-4 hover:shadow-md hover:border-primary/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]">calendar_today</span>
                </div>
                <span class="text-[11px] font-semibold text-on-surface-variant/70 uppercase tracking-wide">Applied</span>
            </div>
            <div class="text-[15px] font-bold text-on-surface">{{ $jobApplication->applied_at?->format('M d, Y') ?? 'Not set' }}</div>
        </div>
        <div class="bg-white border border-outline-variant/50 rounded-xl p-4 hover:shadow-md hover:border-primary/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]">location_on</span>
                </div>
                <span class="text-[11px] font-semibold text-on-surface-variant/70 uppercase tracking-wide">Location</span>
            </div>
            <div class="text-[15px] font-bold text-on-surface truncate">{{ $jobApplication->location_city ?? ($jobApplication->location_type?->label() ?? 'Not set') }}</div>
        </div>
        @if ($jobApplication->salary_min || $jobApplication->salary_max)
        <div class="bg-white border border-outline-variant/50 rounded-xl p-4 hover:shadow-md hover:border-primary/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-secondary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary text-[18px]">payments</span>
                </div>
                <span class="text-[11px] font-semibold text-on-surface-variant/70 uppercase tracking-wide">Salary</span>
            </div>
            <div class="text-[15px] font-bold text-on-surface">
                {{ $jobApplication->salary_min ? number_format($jobApplication->salary_min) : '' }}{{ $jobApplication->salary_min && $jobApplication->salary_max ? ' - ' : '' }}{{ $jobApplication->salary_max ? number_format($jobApplication->salary_max) : '' }} {{ $jobApplication->currency }}
            </div>
        </div>
        <div class="bg-white border border-outline-variant/50 rounded-xl p-4 hover:shadow-md hover:border-primary/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]">work_outline</span>
                </div>
                <span class="text-[11px] font-semibold text-on-surface-variant/70 uppercase tracking-wide">Type</span>
            </div>
            <div class="text-[15px] font-bold text-on-surface">{{ $jobApplication->location_type?->label() ?? 'Not specified' }}</div>
        </div>
        @else
        <div class="bg-white border border-outline-variant/50 rounded-xl p-4 hover:shadow-md hover:border-primary/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]">work_outline</span>
                </div>
                <span class="text-[11px] font-semibold text-on-surface-variant/70 uppercase tracking-wide">Type</span>
            </div>
            <div class="text-[15px] font-bold text-on-surface">{{ $jobApplication->location_type?->label() ?? 'Not specified' }}</div>
        </div>
        <div class="bg-white border border-outline-variant/50 rounded-xl p-4 hover:shadow-md hover:border-primary/30 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg {{ $pc['bg'] }} flex items-center justify-center">
                    <span class="material-symbols-outlined {{ $pc['text'] }} text-[18px]">flag</span>
                </div>
                <span class="text-[11px] font-semibold text-on-surface-variant/70 uppercase tracking-wide">Priority</span>
            </div>
            <div class="text-[15px] font-bold text-on-surface">{{ ucfirst($jobApplication->priority) }}</div>
        </div>
        @endif
    </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Left column -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Progress -->
            <section class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-outline-variant/30">
                    <h3 class="text-[14px] font-semibold text-on-surface">Application Progress</h3>
                </div>
                <div class="p-5">
                    @php
                        $statuses = [
                            ['key' => 'applied', 'label' => 'Applied', 'icon' => 'description'],
                            ['key' => 'in_review', 'label' => 'In Review', 'icon' => 'visibility'],
                            ['key' => 'hr_interview', 'label' => 'HR Interview', 'icon' => 'group'],
                            ['key' => 'technical_interview', 'label' => 'Technical Interview', 'icon' => 'code'],
                            ['key' => 'final_interview', 'label' => 'Final Interview', 'icon' => 'verified'],
                            ['key' => 'offer', 'label' => 'Offer', 'icon' => 'celebration'],
                            ['key' => 'accepted', 'label' => 'Accepted', 'icon' => 'check_circle'],
                            ['key' => 'rejected', 'label' => 'Rejected', 'icon' => 'cancel'],
                            ['key' => 'ghosted', 'label' => 'Ghosted', 'icon' => 'person_off'],
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
                                    <button type="submit" class="w-full text-left flex items-center gap-4 cursor-pointer py-2.5 px-3 -mx-3 rounded-lg hover:bg-surface-container/50 transition-all">
                            @else
                                <div class="flex items-center gap-4 py-2.5">
                            @endif
                                <div class="flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 {{ $isActive ? 'bg-primary/10 text-primary ring-2 ring-primary/30' : ($isPast ? 'bg-primary/5 text-primary' : 'bg-surface-container text-on-surface-variant/30') }} {{ $canUpdate ? 'group-hover:ring-2 group-hover:ring-primary/50' : '' }} flex-shrink-0">
                                    @if ($isPast && !$isActive)
                                        <span class="material-symbols-filled text-[16px]">check</span>
                                    @else
                                        <span class="material-symbols-outlined text-[16px]">{{ $step['icon'] }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-medium transition-colors duration-200 {{ $isActive ? 'text-primary' : ($isPast ? 'text-on-surface' : 'text-on-surface-variant/40') }} {{ $canUpdate ? 'group-hover:text-primary' : '' }}">{{ $step['label'] }}</p>
                                    @if ($isActive)
                                        <p class="text-[11px] text-primary/70 font-medium mt-0.5">Current status</p>
                                    @elseif ($canUpdate)
                                        <p class="text-[11px] text-on-surface-variant/40 mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">Click to update</p>
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

            <!-- Notes -->
            @if ($jobApplication->notes)
                <section class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-outline-variant/30">
                        <h3 class="text-[14px] font-semibold text-on-surface">Notes</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-[14px] text-on-surface-variant/80 leading-relaxed whitespace-pre-line">{{ $jobApplication->notes }}</p>
                    </div>
                </section>
            @endif

            <!-- Activity Log -->
            @if ($jobApplication->activities->isNotEmpty())
                <section class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-outline-variant/30">
                        <h3 class="text-[14px] font-semibold text-on-surface">Activity Log</h3>
                    </div>
                    <div class="divide-y divide-outline-variant/20">
                        @foreach ($jobApplication->activities->sortByDesc('created_at')->take(10) as $log)
                            <div class="px-4 py-3 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-on-surface-variant/50 text-[16px]">history</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] text-on-surface-variant/80">{{ $log->description ?? $log->action }}</p>
                                    <p class="text-[11px] text-on-surface-variant/40 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <!-- Right column -->
        <div class="space-y-5">

            <!-- Company -->
            <div class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-outline-variant/30 flex items-center justify-between">
                    <h4 class="text-[13px] font-semibold text-on-surface">Company</h4>
                    @if ($jobApplication->company)
                        <a href="{{ route('companies.show', $jobApplication->company) }}" class="text-[11px] font-medium text-primary hover:underline">View &rarr;</a>
                    @endif
                </div>
                @if ($jobApplication->company)
                    @php $company = $jobApplication->company; @endphp
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-primary-container text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[14px] font-semibold text-on-surface truncate">{{ $company->name }}</p>
                                @if ($company->industry)
                                    <p class="text-[11px] text-on-surface-variant/60 truncate">{{ $company->industry }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2">
                            @if ($company->location)
                                <div class="flex items-center gap-2 text-[12px] text-on-surface-variant/70">
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant/40">location_on</span>
                                    <span>{{ $company->location }}</span>
                                </div>
                            @endif
                            @if ($company->website)
                                <div class="flex items-center gap-2 text-[12px]">
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant/40">language</span>
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline truncate">{{ parse_url($company->website, PHP_URL_HOST) ?: $company->website }}</a>
                                </div>
                            @endif
                            <div class="flex items-center gap-2 text-[12px] text-on-surface-variant/70">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant/40">work</span>
                                <span>{{ $company->jobApplications->count() }} application{{ $company->jobApplications->count() !== 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        @if ($company->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-outline-variant/20">
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
                        <span class="material-symbols-outlined text-on-surface-variant/30 text-[32px] mb-1">business</span>
                        <p class="text-[12px] text-on-surface-variant/50 mb-3">No company linked</p>
                        <a href="{{ route('job-applications.edit', $jobApplication) }}" class="px-3 py-1.5 bg-primary text-white rounded-lg text-[12px] font-medium hover:bg-primary/90 transition-all inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">add</span>
                            Link Company
                        </a>
                    </div>
                @endif
            </div>

            <!-- Contacts -->
            @if ($jobApplication->company && $jobApplication->company->contacts->isNotEmpty())
            <div class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-outline-variant/30 flex items-center justify-between">
                    <h4 class="text-[13px] font-semibold text-on-surface">Contacts</h4>
                    <span class="bg-primary/10 text-primary text-[11px] font-medium px-2 py-0.5 rounded-full">{{ $jobApplication->company->contacts->count() }}</span>
                </div>
                <div class="divide-y divide-outline-variant/20">
                    @foreach ($jobApplication->company->contacts as $contact)
                        <a href="{{ route('contacts.show', $contact) }}" class="px-4 py-3 flex items-center gap-3 hover:bg-surface-container/50 transition-all group">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-primary to-primary-container text-white text-xs font-semibold flex-shrink-0">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-medium text-on-surface group-hover:text-primary transition-colors truncate">{{ $contact->name }}</p>
                                @if ($contact->role || $contact->email)
                                    <p class="text-[11px] text-on-surface-variant/50 truncate">{{ $contact->role ?? $contact->email }}</p>
                                @endif
                            </div>
                            <span class="material-symbols-outlined text-outline-variant/50 group-hover:text-primary transition-colors text-[16px] flex-shrink-0">chevron_right</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Links -->
            @if (!empty($jobApplication->links))
                <div class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-outline-variant/30">
                        <h4 class="text-[13px] font-semibold text-on-surface">Links</h4>
                    </div>
                    <div class="divide-y divide-outline-variant/20">
                        @if (!empty($jobApplication->links['job_posting']))
                            <a href="{{ $jobApplication->links['job_posting'] }}" target="_blank" rel="noopener noreferrer" class="px-4 py-3 flex items-center gap-3 hover:bg-surface-container/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-primary/5 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/10 transition-colors">
                                    <span class="material-symbols-outlined text-primary/60 group-hover:text-primary text-[16px]">open_in_new</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-medium text-on-surface group-hover:text-primary transition-colors">Job Posting</p>
                                    <p class="text-[11px] text-on-surface-variant/40 truncate">{{ $jobApplication->links['job_posting'] }}</p>
                                </div>
                            </a>
                        @endif
                        @if (!empty($jobApplication->links['company_website']))
                            <a href="{{ $jobApplication->links['company_website'] }}" target="_blank" rel="noopener noreferrer" class="px-4 py-3 flex items-center gap-3 hover:bg-surface-container/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-primary/5 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/10 transition-colors">
                                    <span class="material-symbols-outlined text-primary/60 group-hover:text-primary text-[16px]">language</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-medium text-on-surface group-hover:text-primary transition-colors">Company Website</p>
                                    <p class="text-[11px] text-on-surface-variant/40 truncate">{{ $jobApplication->links['company_website'] }}</p>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Interviews -->
            <div class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-outline-variant/30 flex justify-between items-center">
                    <h4 class="text-[13px] font-semibold text-on-surface">Interviews</h4>
                    <span class="bg-primary/10 text-primary text-[11px] font-medium px-2 py-0.5 rounded-full">{{ $jobApplication->interviews->count() }}</span>
                </div>
                @if ($jobApplication->interviews->isEmpty())
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <span class="material-symbols-outlined text-on-surface-variant/30 text-[32px] mb-1">event</span>
                        <p class="text-[12px] text-on-surface-variant/50 mb-2">No interviews yet</p>
                        <a href="{{ route('interviews.create', ['job_application_id' => $jobApplication->id]) }}" class="px-3 py-1.5 bg-primary text-white rounded-lg text-[12px] font-medium hover:bg-primary/90 transition-all inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">add</span>
                            Add Interview
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-outline-variant/20">
                        @foreach ($jobApplication->interviews->sortByDesc('scheduled_at') as $interview)
                            <a href="{{ route('interviews.show', $interview) }}" class="px-4 py-3 flex items-center gap-3 hover:bg-surface-container/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0 group-hover:bg-primary/10 transition-colors">
                                    <span class="material-symbols-outlined text-on-surface-variant/50 group-hover:text-primary text-[16px] transition-colors">event</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-medium text-on-surface group-hover:text-primary transition-colors truncate">{{ $interview->type }}</p>
                                    <p class="text-[11px] text-on-surface-variant/40 mt-0.5">{{ $interview->scheduled_at?->format('M d, Y g:i A') }}</p>
                                    @if ($interview->result)
                                        <p class="text-[11px] text-on-surface-variant/50 mt-0.5">{{ $interview->result }}</p>
                                    @endif
                                </div>
                                <span class="material-symbols-outlined text-outline-variant/50 group-hover:text-primary transition-colors text-[16px] flex-shrink-0">chevron_right</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="px-4 py-3 border-t border-outline-variant/20">
                        <a href="{{ route('interviews.create', ['job_application_id' => $jobApplication->id]) }}" class="text-[12px] text-primary font-medium hover:underline inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">add</span>
                            Add Interview
                        </a>
                    </div>
                @endif
            </div>

            <!-- Documents -->
            <div class="bg-white border border-outline-variant/50 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-outline-variant/30">
                    <h4 class="text-[13px] font-semibold text-on-surface">Documents</h4>
                </div>
                @if ($jobApplication->documents->isEmpty())
                    <div class="flex flex-col items-center justify-center py-4 text-center">
                        <span class="material-symbols-outlined text-on-surface-variant/30 text-[32px] mb-1">folder_open</span>
                        <p class="text-[12px] text-on-surface-variant/50">No documents yet</p>
                    </div>
                @else
                    <div class="divide-y divide-outline-variant/20">
                        @foreach ($jobApplication->documents as $doc)
                            <div class="px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-on-surface-variant/50 text-[16px]">description</span>
                                    </div>
                                    <a href="{{ route('documents.download', $doc) }}" class="text-[13px] font-medium text-on-surface hover:text-primary truncate">{{ $doc->name }}</a>
                                </div>
                                <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] text-error font-medium hover:underline">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="px-4 py-3 border-t border-outline-variant/20">
                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="documentable_type" value="App\Models\JobApplication">
                        <input type="hidden" name="documentable_id" value="{{ $jobApplication->id }}">
                        <input type="file" name="file" class="block w-full text-[12px] text-on-surface-variant/60 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                        <button type="submit" class="text-[12px] text-primary font-medium hover:underline flex-shrink-0">Upload</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

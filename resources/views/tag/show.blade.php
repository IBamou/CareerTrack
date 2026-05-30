<x-app-layout>
    <x-slot name="header">Tag: {{ $tag->name }}</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        @if (session('status'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <a href="{{ route('tags.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
                Back to Tags
            </a>
        </div>

        <!-- Tag Header -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}20">
                    <i class="fas fa-tag text-[28px]" style="color: {{ $tag->color ?? '#0891b2' }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $tag->name }}</h2>
                        <span class="inline-block w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}"></span>
                    </div>
                    <div class="flex items-center gap-3 mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                        <span>{{ $tag->job_applications_count }} application{{ $tag->job_applications_count !== 1 ? 's' : '' }}</span>
                        @if ($tag->companies_count)
                            <span>&middot; {{ $tag->companies_count }} compan{{ $tag->companies_count !== 1 ? 'ies' : 'y' }}</span>
                        @endif
                        @if ($tag->contacts_count)
                            <span>&middot; {{ $tag->contacts_count }} contact{{ $tag->contacts_count !== 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Applications</h3>
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $tag->job_applications_count }} total</span>
            </div>
            @if ($tag->jobApplications->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <i class="fas fa-briefcase text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No applications with this tag</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($tag->jobApplications as $app)
                        <a href="{{ route('job-applications.show', $app) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">{{ $app->job_title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $app->company?->name ?? 'No company' }}
                                    @if ($app->applied_at)
                                        &middot; {{ $app->applied_at->format('M d, Y') }}
                                    @endif
                                </p>
                            </div>
                            <x-status-badge :status="$app->status" size="sm" />
                            <i class="fas fa-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 transition-colors text-xs"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Companies -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Companies</h3>
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $tag->companies_count }} total</span>
            </div>
            @if ($tag->companies->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <i class="fas fa-building text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No companies with this tag</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($tag->companies as $company)
                        <a href="{{ route('companies.show', $company) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">{{ $company->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $company->industry ?? 'No industry' }}
                                    @if ($company->location)
                                        &middot; {{ $company->location }}
                                    @endif
                                </p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 transition-colors text-xs"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Contacts -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Contacts</h3>
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $tag->contacts_count }} total</span>
            </div>
            @if ($tag->contacts->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <i class="fas fa-user-friends text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No contacts with this tag</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($tag->contacts as $contact)
                        <a href="{{ route('contacts.show', $contact) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">{{ $contact->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $contact->role ?? 'No role' }}
                                    @if ($contact->company)
                                        &middot; {{ $contact->company->name }}
                                    @endif
                                </p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 transition-colors text-xs"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>

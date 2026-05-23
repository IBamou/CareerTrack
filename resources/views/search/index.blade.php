<x-app-layout>
    <x-slot name="header">Search</x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="GET" action="{{ route('search') }}" class="mb-6">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="{{ $query }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl py-3 pl-12 pr-24 text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:border-[#2563eb] dark:focus:border-blue-500 focus:ring-2 focus:ring-[#2563eb]/10 outline-none transition-all" placeholder="Search applications, companies, interviews..." autofocus />
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-4 py-2 bg-[#2563eb] hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">Search</button>
            </div>
        </form>

        @if ($query)
        <div class="flex items-center gap-1 mb-6 border-b border-slate-200 dark:border-slate-700 overflow-x-auto">
            <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $type === 'all' ? 'border-[#2563eb] text-[#2563eb] dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-t-lg' }}">
                All ({{ $totalResults }})
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'applications']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $type === 'applications' ? 'border-[#2563eb] text-[#2563eb] dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-t-lg' }}">
                Applications ({{ $appCount }})
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'companies']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $type === 'companies' ? 'border-[#2563eb] text-[#2563eb] dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-t-lg' }}">
                Companies ({{ $companyCount }})
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'contacts']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $type === 'contacts' ? 'border-[#2563eb] text-[#2563eb] dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-t-lg' }}">
                Contacts ({{ $contactCount }})
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'tags']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $type === 'tags' ? 'border-[#2563eb] text-[#2563eb] dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-t-lg' }}">
                Tags ({{ $tagCount }})
            </a>
        </div>

        @if ($totalResults === 0)
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-8 text-center">
            <i class="fas fa-search text-4xl text-slate-300 dark:text-slate-600 mb-3"></i>
            <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-1">No results found</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Try different keywords or broaden your search.</p>
        </div>
        @else
        @if (($type === 'all' || $type === 'applications') && $applications->isNotEmpty())
        <section class="mb-8">
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                <i class="fas fa-briefcase text-slate-400"></i>
                Applications
            </h3>
            <div class="space-y-2">
                @foreach ($applications as $app)
                <a href="{{ route('job-applications.show', $app) }}" class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-[#2563eb] to-blue-400 text-white text-xs font-semibold flex-shrink-0">
                            {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $app->job_title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $app->company?->name ?? 'No company' }} @if ($app->location_city) &middot; {{ $app->location_city }} @endif</p>
                        </div>
                        <x-status-badge :status="$app->status" size="sm" />
                    </div>
                </a>
                @endforeach
            </div>
            @if ($applications instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $applications->links() }}
            </div>
            @endif
        </section>
        @endif

        @if (($type === 'all' || $type === 'companies') && $companies->isNotEmpty())
        <section class="mb-8">
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                <i class="fas fa-building text-slate-400"></i>
                Companies
            </h3>
            <div class="space-y-2">
                @foreach ($companies as $company)
                <a href="{{ route('companies.show', $company) }}" class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-[#2563eb] to-blue-400 text-white text-xs font-semibold flex-shrink-0">
                            {{ strtoupper(substr($company->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $company->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $company->industry ?? 'No industry' }} @if ($company->location) &middot; {{ $company->location }} @endif</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @if ($companies instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $companies->links() }}
            </div>
            @endif
        </section>
        @endif

        @if (($type === 'all' || $type === 'tags') && $tags->isNotEmpty())
        <section class="mb-8">
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                <i class="fas fa-tags text-slate-400"></i>
                Tags
            </h3>
            <div class="space-y-2">
                @foreach ($tags as $tag)
                <a href="{{ route('tags.show', $tag) }}" class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}20">
                            <i class="fas fa-tag" style="color: {{ $tag->color ?? '#0891b2' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white flex items-center gap-2">
                                {{ $tag->name }}
                                <span class="inline-block w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}"></span>
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $tag->job_applications_count }} application{{ $tag->job_applications_count !== 1 ? 's' : '' }}
                                @if ($tag->companies_count) &middot; {{ $tag->companies_count }} compan{{ $tag->companies_count !== 1 ? 'ies' : 'y' }} @endif
                                @if ($tag->contacts_count) &middot; {{ $tag->contacts_count }} contact{{ $tag->contacts_count !== 1 ? 's' : '' }} @endif
                            </p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @if ($tags instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $tags->links() }}
            </div>
            @endif
        </section>
        @endif

        @if (($type === 'all' || $type === 'contacts') && $contacts->isNotEmpty())
        <section class="mb-8">
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                <i class="fas fa-user-friends text-slate-400"></i>
                Contacts
            </h3>
            <div class="space-y-2">
                @foreach ($contacts as $contact)
                <a href="{{ route('contacts.show', $contact) }}" class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-[#2563eb] to-blue-400 text-white text-xs font-semibold flex-shrink-0">
                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $contact->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $contact->role ?? 'No role' }} @if ($contact->company) &middot; {{ $contact->company->name }} @endif</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @if ($contacts instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
            @endif
        </section>
        @endif
        @endif
        @endif
    </div>
</x-app-layout>
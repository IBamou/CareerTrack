<x-app-layout>
    <x-slot name="header">Search</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-3xl mx-auto">
            <form method="GET" action="{{ route('search') }}" class="mb-6">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ $query }}" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl py-3 pl-12 pr-24 text-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none transition-all" placeholder="Search applications, companies..." autofocus />
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">Search</button>
                </div>
            </form>

            @if ($query)
            <div class="flex items-center gap-1 mb-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors {{ $type === 'all' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-t-lg' }}">
                    All ({{ $totalResults }})
                </a>
                <a href="{{ route('search', ['q' => $query, 'type' => 'applications']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors {{ $type === 'applications' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-t-lg' }}">
                    Applications ({{ $appCount }})
                </a>
                <a href="{{ route('search', ['q' => $query, 'type' => 'companies']) }}" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors {{ $type === 'companies' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-t-lg' }}">
                    Companies ({{ $companyCount }})
                </a>
            </div>

            @if ($totalResults === 0)
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">No results found</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Try different keywords or broaden your search.</p>
            </div>
            @else
            @if (($type === 'all' || $type === 'applications') && $applications->isNotEmpty())
            <section class="mb-8">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Applications
                </h3>
                <div class="space-y-2">
                    @foreach ($applications as $app)
                    <a href="{{ route('job-applications.show', $app) }}" class="block bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-sm transition-all">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 text-white text-xs font-semibold flex-shrink-0">
                                {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $app->job_title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $app->company?->name ?? 'No company' }} @if ($app->location_city) &middot; {{ $app->location_city }} @endif</p>
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
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Companies
                </h3>
                <div class="space-y-2">
                    @foreach ($companies as $company)
                    <a href="{{ route('companies.show', $company) }}" class="block bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-sm transition-all">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 text-white text-xs font-semibold flex-shrink-0">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $company->industry ?? 'No industry' }} @if ($company->location) &middot; {{ $company->location }} @endif</p>
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
            @endif
            @endif
        </div>
    </div>
</x-app-layout>

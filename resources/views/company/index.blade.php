<x-app-layout>
    <x-slot name="header">Companies</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Companies</h2>
                <a href="{{ route('companies.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Company
                </a>
            </div>

            @if ($companies->isEmpty())
                <x-empty-state
                    title="No companies yet"
                    message="Add companies to your directory to track them alongside your applications."
                    :action="route('companies.create')"
                    action-label="Add Company"
                />
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($companies as $company)
                        <a href="{{ route('companies.show', $company) }}" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-sm transition-all group">
                            <div class="flex items-start gap-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 text-white text-sm font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($company->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">{{ $company->name }}</h3>
                                    @if ($company->industry)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $company->industry }}</p>
                                    @endif
                                    @if ($company->location)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $company->location }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $company->job_applications_count }} application{{ $company->job_applications_count !== 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $companies->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

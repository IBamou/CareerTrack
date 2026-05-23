<x-app-layout>
    <x-slot name="header">Tag: {{ $tag->name }}</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-5xl mx-auto space-y-6">

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                <a href="{{ route('tags.index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors font-medium">Tags</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-gray-900 dark:text-white font-semibold">{{ $tag->name }}</span>
            </nav>

            <!-- Tag Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}20">
                        <span class="material-symbols-outlined text-[28px]" style="color: {{ $tag->color ?? '#0891b2' }}">label</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $tag->name }}</h2>
                            <span class="inline-block w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}"></span>
                        </div>
                        <div class="flex items-center gap-3 mt-1.5 text-sm text-gray-500 dark:text-gray-400">
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
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Applications</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tag->job_applications_count }} total</span>
                </div>
                @if ($tag->jobApplications->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <span class="material-symbols-outlined text-[40px] text-gray-300 dark:text-gray-600 mb-2">work_outline</span>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No applications with this tag</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($tag->jobApplications as $app)
                            <a href="{{ route('job-applications.show', $app) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($app->company?->name ?? $app->job_title, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">{{ $app->job_title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $app->company?->name ?? 'No company' }}
                                        @if ($app->applied_at)
                                            &middot; {{ $app->applied_at->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <x-status-badge :status="$app->status" size="sm" />
                                <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition-colors text-[18px]">chevron_right</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Companies -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Companies</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tag->companies_count }} total</span>
                </div>
                @if ($tag->companies->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <span class="material-symbols-outlined text-[40px] text-gray-300 dark:text-gray-600 mb-2">business</span>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No companies with this tag</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($tag->companies as $company)
                            <a href="{{ route('companies.show', $company) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container text-white text-sm font-bold flex-shrink-0">
                                    {{ strtoupper(substr($company->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">{{ $company->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $company->industry ?? 'No industry' }}
                                        @if ($company->location)
                                            &middot; {{ $company->location }}
                                        @endif
                                    </p>
                                </div>
                                <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition-colors text-[18px]">chevron_right</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Contacts -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Contacts</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tag->contacts_count }} total</span>
                </div>
                @if ($tag->contacts->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <span class="material-symbols-outlined text-[40px] text-gray-300 dark:text-gray-600 mb-2">people</span>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No contacts with this tag</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($tag->contacts as $contact)
                            <a href="{{ route('contacts.show', $contact) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary-container text-white text-sm font-bold flex-shrink-0">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">{{ $contact->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $contact->role ?? 'No role' }}
                                        @if ($contact->company)
                                            &middot; {{ $contact->company->name }}
                                        @endif
                                    </p>
                                </div>
                                <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition-colors text-[18px]">chevron_right</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
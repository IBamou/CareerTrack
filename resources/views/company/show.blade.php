<x-app-layout>
    <x-slot name="header">{{ $company->name }}</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-5xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Companies
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Archive
                        </button>
                        <x-confirm-action-modal name="archive" title="Archive Company?" message="This will move the company to the archive. You can restore it later." :action="route('companies.archive', $company)" method="delete" button="Archive" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-xl font-bold">
                    {{ strtoupper(substr($company->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $company->industry ?? 'No industry' }}
                        @if ($company->location)
                            &middot; {{ $company->location }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-section-card title="Details" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'>
                    <dl class="space-y-3">
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Website</dt><dd class="mt-0.5">@if ($company->website)<a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">{{ parse_url($company->website, PHP_URL_HOST) }}</a>@else<span class="text-sm text-gray-500 dark:text-gray-400">Not provided</span>@endif</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Location</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $company->location ?? 'Not specified' }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Applications</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $company->job_applications_count ?? 0 }}</dd></div>
                    </dl>
                </x-section-card>

                @if ($company->notes)
                <x-section-card title="Notes" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $company->notes }}</p>
                </x-section-card>
                @endif
            </div>

            @if ($jobApplications->isNotEmpty())
                <x-section-card title="Applications" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($jobApplications as $app)
                            <a href="{{ route('job-applications.show', $app) }}" class="flex items-center gap-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 -mx-4 px-4 transition-colors">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($app->job_title, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $app->job_title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $app->created_at?->format('M d, Y') }}</p>
                                </div>
                                <x-status-badge :status="$app->status" size="sm" />
                            </a>
                        @endforeach
                    </div>
                </x-section-card>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Contacts -->
                <x-section-card title="Contacts" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'>
                    @if ($company->contacts->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No contacts yet.</p>
                    @else
                        <div class="space-y-2">
                            @foreach ($company->contacts as $contact)
                                <a href="{{ route('contacts.show', $contact) }}" class="flex items-center gap-3 p-3 -mx-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-sm font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $contact->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $contact->role ?? 'No role' }} @if ($contact->email) &middot; {{ $contact->email }} @endif</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="pt-3 mt-2 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('contacts.create') }}?company_id={{ $company->id }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Contact
                        </a>
                    </div>
                </x-section-card>

                <!-- Documents -->
                <x-section-card title="Documents" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>'>
                    @if ($company->documents->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No documents uploaded.</p>
                    @else
                        <div class="space-y-2">
                            @foreach ($company->documents as $doc)
                                <div class="flex items-center justify-between p-2 -mx-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <a href="{{ route('documents.download', $doc) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 truncate">{{ $doc->name }}</a>
                                    </div>
                                    <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline flex-shrink-0">Delete</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="pt-3 mt-2 border-t border-gray-100 dark:border-gray-700">
                        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="documentable_type" value="App\Models\Company">
                            <input type="hidden" name="documentable_id" value="{{ $company->id }}">
                            <input type="file" name="file" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 dark:file:bg-emerald-500/10 file:text-emerald-600 dark:file:text-emerald-400 hover:file:bg-emerald-100 dark:hover:file:bg-emerald-500/20 transition-colors">
                            <button type="submit" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline flex-shrink-0">Upload</button>
                        </form>
                    </div>
                </x-section-card>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">{{ $company->name }}</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        @if (session('status'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
                Back to Companies
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <i class="fas fa-edit text-xs"></i>
                    Edit
                </a>
                <div>
                    <button type="button" @click="$dispatch('open-modal-archive')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-archive text-xs"></i>
                        Archive
                    </button>
                    <x-confirm-action-modal name="archive" title="Archive Company?" message="This will move the company to the archive. You can restore it later." :action="route('companies.archive', $company)" method="delete" button="Archive" />
                </div>
            </div>
        </div>

        <div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $company->name }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    @if ($company->industry)
                        {{ $company->industry }}
                    @else
                        <span class="italic">No industry specified</span>
                    @endif
                    @if ($company->location)
                        &middot; {{ $company->location }}
                    @endif
                </p>
            </div>
        </div>

        <x-section-card title="Details">
            <dl class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-slate-100 dark:divide-slate-700 -mx-4">
                <div class="px-4 text-center">
                    <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide flex items-center justify-center gap-1.5 mb-1">
                        <i class="fas fa-globe"></i> Website
                    </dt>
                    <dd>
                        @if ($company->website)
                            <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-[#2563eb] dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                {{ parse_url($company->website, PHP_URL_HOST) ?: $company->website }}
                            </a>
                        @else
                            <span class="text-sm text-slate-400 dark:text-slate-500 italic">No website provided</span>
                        @endif
                    </dd>
                </div>
                <div class="px-4 text-center">
                    <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide flex items-center justify-center gap-1.5 mb-1">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </dt>
                    <dd class="text-sm font-semibold text-slate-900 dark:text-white">
                        @if ($company->location)
                            {{ $company->location }}
                        @else
                            <span class="text-sm text-slate-400 dark:text-slate-500 italic">No location specified</span>
                        @endif
                    </dd>
                </div>
                <div class="px-4 text-center">
                    <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide flex items-center justify-center gap-1.5 mb-1">
                        <i class="fas fa-building"></i> Industry
                    </dt>
                    <dd class="text-sm font-semibold text-slate-900 dark:text-white">
                        @if ($company->industry)
                            {{ $company->industry }}
                        @else
                            <span class="text-sm text-slate-400 dark:text-slate-500 italic">No industry specified</span>
                        @endif
                    </dd>
                </div>
                <div class="px-4 text-center">
                    <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide flex items-center justify-center gap-1.5 mb-1">
                        <i class="fas fa-briefcase"></i> Applications
                    </dt>
                    <dd class="text-2xl font-bold text-slate-900 dark:text-white">{{ $company->job_applications_count ?? 0 }}</dd>
                </div>
            </dl>
        </x-section-card>

        <x-section-card title="Applications">
            @if ($jobApplications->isNotEmpty())
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($jobApplications as $app)
                        <a href="{{ route('job-applications.show', $app) }}" class="flex items-center gap-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 -mx-4 px-4 transition-colors">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-500/10 text-[#2563eb] dark:text-blue-400 text-xs font-semibold flex-shrink-0">
                                {{ strtoupper(substr($app->job_title, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $app->job_title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $app->created_at?->format('M d, Y') }}</p>
                            </div>
                            <x-status-badge :status="$app->status" size="sm" />
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <i class="fas fa-briefcase text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No applications for this company yet</p>
                    <a href="{{ route('job-applications.create') }}" class="mt-3 px-4 py-2 bg-[#2563eb] text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center gap-1.5">
                        <i class="fas fa-plus text-xs"></i>
                        Add Application
                    </a>
                </div>
            @endif
        </x-section-card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-section-card title="Contacts" class="flex flex-col">
                <div class="flex-1">
                    @if ($contacts->isNotEmpty())
                        <div class="space-y-2">
                            @foreach ($contacts as $contact)
                                <a href="{{ route('contacts.show', $contact) }}" class="flex items-center gap-3 p-3 -mx-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-[#2563eb] dark:text-blue-400 text-sm font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-[#2563eb] dark:group-hover:text-blue-400 transition-colors">{{ $contact->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $contact->role ?? 'No role' }} @if ($contact->email) &middot; {{ $contact->email }} @endif</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-slate-400 group-hover:text-[#2563eb] transition-colors text-xs flex-shrink-0"></i>
                                </a>
                            @endforeach
                        </div>
                        @if ($contacts->hasPages())
                            <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-700">
                                {{ $contacts->links() }}
                            </div>
                        @endif
                    @else
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <i class="fas fa-user-friends text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No contacts yet</p>
                        </div>
                    @endif
                </div>
                <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('contacts.create') }}?company_id={{ $company->id }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-[#2563eb] dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                        <i class="fas fa-plus text-xs"></i>
                        Add Contact
                    </a>
                </div>
            </x-section-card>

            <x-section-card title="Documents" class="flex flex-col">
                <div class="flex-1">
                    @if ($company->documents->isNotEmpty())
                        <div class="space-y-2">
                            @foreach ($company->documents as $doc)
                                <div class="flex items-center justify-between p-2 -mx-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <i class="fas fa-file text-slate-400 flex-shrink-0"></i>
                                        <a href="{{ route('documents.download', $doc) }}" class="text-sm font-medium text-slate-900 dark:text-white hover:text-[#2563eb] dark:hover:text-blue-400 truncate">{{ $doc->name }}</a>
                                    </div>
                                    <div>
                                        <button type="button" @click="$dispatch('open-modal-delete_doc_{{ $doc->id }}')" class="text-xs text-red-500 hover:underline flex-shrink-0">Delete</button>
                                        <x-confirm-action-modal name="delete-doc-{{ $doc->id }}" title="Delete Document?" message="Are you sure you want to delete this document?" :action="route('documents.destroy', $doc)" method="delete" button="Delete" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <i class="fas fa-folder-open text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No documents uploaded</p>
                        </div>
                    @endif
                </div>
                <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-700">
                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="documentable_type" value="App\Models\Company">
                        <input type="hidden" name="documentable_id" value="{{ $company->id }}">
                        <input type="file" name="file" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 dark:file:bg-blue-500/10 file:text-[#2563eb] dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-500/20 transition-colors cursor-pointer">
                        <button type="submit" class="text-xs font-medium text-[#2563eb] dark:text-blue-400 hover:underline flex-shrink-0">Upload</button>
                    </form>
                </div>
            </x-section-card>
        </div>

        <x-section-card title="Notes">
            @if ($company->notes)
                <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $company->notes }}</p>
            @else
                <p class="text-sm text-slate-400 dark:text-slate-500 italic">No notes added</p>
            @endif
        </x-section-card>
    </div>
</x-app-layout>

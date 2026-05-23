<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('companies.index') }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Archived Companies</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Restore or permanently delete past companies</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">

        @if (session('status'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl text-sm font-medium text-blue-700 dark:text-blue-300">
                {{ session('status') }}
            </div>
        @endif

        @if ($companies->isEmpty())
            <x-empty-state
                title="No archived companies"
                message="Archived companies will appear here."
                :action="route('companies.index')"
                label="Go to Companies"
            />
        @else
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($companies as $company)
                        <div class="p-4 sm:p-5 opacity-75 hover:opacity-100 transition-opacity">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-building text-slate-400"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $company->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                        {{ $company->industry ?? 'No industry' }}
                                        @if ($company->location)
                                            &middot; {{ $company->location }}
                                        @endif
                                        &middot; Archived {{ $company->deleted_at?->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="flex gap-2 flex-shrink-0">
                                    <x-secondary-button type="button" @click="$dispatch('open-modal-restore_{{ $company->id }}')">
                                        <i class="fas fa-undo text-xs mr-1.5"></i>
                                        Restore
                                    </x-secondary-button>
                                    <x-confirm-action-modal name="restore-{{ $company->id }}" title="Restore Company?" message="This will restore the company from the archive." :action="route('companies.restore', $company)" method="post" button="Restore" />

                                    <x-danger-button type="button" @click="$dispatch('open-modal-force_delete_{{ $company->id }}')">
                                        <i class="fas fa-trash text-xs mr-1.5"></i>
                                        Delete
                                    </x-danger-button>
                                    <x-confirm-action-modal name="force-delete-{{ $company->id }}" title="Permanently Delete?" message="This action cannot be undone. The company and all its data will be permanently removed." :action="route('companies.forceDelete', $company)" method="delete" button="Delete Forever" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

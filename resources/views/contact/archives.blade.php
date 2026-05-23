<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('contacts.index') }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Archived Contacts</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Restore or permanently delete past contacts</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">

        @if (session('status'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl text-sm font-medium text-blue-700 dark:text-blue-300">
                {{ session('status') }}
            </div>
        @endif

        @if ($contacts->isEmpty())
            <x-empty-state
                title="No archived contacts"
                message="Archived contacts will appear here."
                :action="route('contacts.index')"
                label="Go to Contacts"
            />
        @else
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($contacts as $contact)
                        <div class="p-4 sm:p-5 opacity-75 hover:opacity-100 transition-opacity">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-slate-400"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $contact->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                        {{ $contact->role ?? 'No role' }}
                                        @if ($contact->company)
                                            &middot; {{ $contact->company->name }}
                                        @endif
                                        &middot; Archived {{ $contact->deleted_at?->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="flex gap-2 flex-shrink-0">
                                    <x-secondary-button type="button" @click="$dispatch('open-modal-restore_{{ $contact->id }}')">
                                        <i class="fas fa-undo text-xs mr-1.5"></i>
                                        Restore
                                    </x-secondary-button>
                                    <x-confirm-action-modal name="restore-{{ $contact->id }}" title="Restore Contact?" message="This will restore the contact from the archive." :action="route('contacts.restore', $contact)" method="post" button="Restore" />

                                    <x-danger-button type="button" @click="$dispatch('open-modal-force_delete_{{ $contact->id }}')">
                                        <i class="fas fa-trash text-xs mr-1.5"></i>
                                        Delete
                                    </x-danger-button>
                                    <x-confirm-action-modal name="force-delete-{{ $contact->id }}" title="Permanently Delete?" message="This action cannot be undone. The contact and all its data will be permanently removed." :action="route('contacts.forceDelete', $contact)" method="delete" button="Delete Forever" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

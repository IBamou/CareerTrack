<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('contacts.index') }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Archived Contacts</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Restore or permanently delete past contacts</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            @if ($contacts->isEmpty())
                <x-empty-state
                    title="No archived contacts"
                    message="Archived contacts will appear here."
                />
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($contacts as $contact)
                            <div class="p-4 sm:p-5 opacity-75 hover:opacity-100 transition-opacity">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $contact->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                            {{ $contact->role ?? 'No role' }}
                                            @if ($contact->company)
                                                &middot; {{ $contact->company->name }}
                                            @endif
                                            &middot; Archived {{ $contact->deleted_at?->diffForHumans() }}
                                        </p>
                                    </div>

                                    <div class="flex gap-2 flex-shrink-0">
                                        <form method="POST" action="{{ route('contacts.restore', $contact) }}">
                                            @csrf
                                            <x-secondary-button type="submit">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Restore
                                            </x-secondary-button>
                                        </form>

                                        <div x-data="{ open: false }">
                                            <x-danger-button @click="open = true">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </x-danger-button>
                                            <x-confirm-action-modal name="force-delete-{{ $contact->id }}" title="Permanently Delete?" message="This action cannot be undone. The contact and all its data will be permanently removed." :action="route('contacts.forceDelete', $contact)" method="delete" button="Delete Forever" />
                                        </div>
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
    </div>
</x-app-layout>

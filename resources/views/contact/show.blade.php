<x-app-layout>
    <x-slot name="header">{{ $contact->name }}</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-5xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <a href="{{ route('contacts.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Contacts
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('contacts.edit', $contact) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <div x-data="{ open: false }">
                        <button type="button" @click="$dispatch('open-modal-archive')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <i class="fas fa-trash text-xs"></i>
                            Archive
                        </button>
                        <x-confirm-action-modal name="archive" title="Archive Contact?" message="This will move the contact to the archive. You can restore it later." :action="route('contacts.archive', $contact)" method="delete" button="Archive" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-xl font-bold">
                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $contact->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $contact->role ?? 'No role' }}
                        @if ($contact->company)
                            &middot; {{ $contact->company->name }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-section-card title="Contact Details">
                    <dl class="space-y-3">
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</dt><dd class="mt-0.5">@if ($contact->email)<a href="mailto:{{ $contact->email }}" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">{{ $contact->email }}</a>@else<span class="text-sm text-gray-500 dark:text-gray-400">Not provided</span>@endif</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Phone</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $contact->phone ?? 'Not provided' }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Role</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $contact->role ?? 'Not specified' }}</dd></div>
                        @if ($contact->company)
                            <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Company</dt><dd class="mt-0.5"><a href="{{ route('companies.show', $contact->company) }}" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">{{ $contact->company->name }}</a></dd></div>
                        @endif
                    </dl>
                </x-section-card>

                @if ($contact->notes)
                <x-section-card title="Notes">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $contact->notes }}</p>
                </x-section-card>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

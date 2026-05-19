<x-app-layout>
    <x-slot name="header">Contacts</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Contacts</h2>
                <a href="{{ route('contacts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Contact
                </a>
            </div>

            @if ($contacts->isEmpty())
                <x-empty-state
                    title="No contacts yet"
                    message="Add contacts to your directory to keep track of your network."
                    :action="route('contacts.create')"
                    action-label="Add Contact"
                />
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($contacts as $contact)
                        <a href="{{ route('contacts.show', $contact) }}" class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-md transition-all group">
                            <div class="flex items-start gap-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 truncate">{{ $contact->name }}</h3>
                                    @if ($contact->role)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $contact->role }}</p>
                                    @endif
                                    @if ($contact->company)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $contact->company->name }}</p>
                                    @endif
                                    @if ($contact->email)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 truncate">{{ $contact->email }}</p>
                                    @elseif ($contact->phone)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $contact->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

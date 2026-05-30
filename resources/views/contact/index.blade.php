<x-app-layout>
    <x-slot name="header">Contacts</x-slot>

    <div class="max-w-7xl mx-auto">

        @if (session('status'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl text-sm font-medium text-blue-700 dark:text-blue-300 flex items-center gap-2">
                <i class="fas fa-check-circle text-blue-500"></i>
                {{ session('status') }}
            </div>
        @endif

        <header class="flex-shrink-0 flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Contacts</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Manage your professional network.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('contacts.archives') }}" class="px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center gap-2 text-sm shadow-sm">
                    <i class="fas fa-archive text-xs"></i> Archived
                </a>
                <a href="{{ route('contacts.create') }}" class="bg-[#2563eb] text-white px-4 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2 text-sm shadow-sm">
                    <i class="fas fa-plus"></i> Add Contact
                </a>
            </div>
        </header>

        <form method="GET" action="{{ route('contacts.index') }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-72">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="q" placeholder="Search contacts..." value="{{ request('q') }}" class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm">
                </div>
                @if (request()->filled('q'))
                    <a href="{{ route('contacts.index') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 underline">Clear</a>
                @endif
            </div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                Total: <span class="font-semibold text-slate-900 dark:text-white">{{ $contacts->total() }}</span>
            </div>
        </form>

        @if ($contacts->isEmpty())
            <x-empty-state
                title="No contacts yet"
                message="Add contacts to your directory to keep track of your network."
                :action="route('contacts.create')"
                label="Add Contact"
            />
        @else
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                            <th class="p-4 pl-6">Name</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">Company</th>
                            <th class="p-4">Contact</th>
                            <th class="p-4 text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                        @foreach ($contacts as $contact)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition">
                                <td class="p-4 pl-6">
                                    <a href="{{ route('contacts.show', $contact) }}" class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-white dark:bg-slate-700 border border-slate-100 dark:border-slate-600 shadow-sm flex items-center justify-center flex-shrink-0 font-bold text-sm text-[#2563eb]">
                                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                                        </div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $contact->name }}</div>
                                    </a>
                                </td>
                                <td class="p-4 text-slate-600 dark:text-slate-400 font-medium">{{ $contact->role ?? '—' }}</td>
                                <td class="p-4 text-slate-600 dark:text-slate-400 font-medium">{{ $contact->company?->name ?? '—' }}</td>
                                <td class="p-4">
                                    @if ($contact->email)
                                        <div class="text-slate-600 dark:text-slate-400 font-medium">{{ $contact->email }}</div>
                                    @elseif ($contact->phone)
                                        <div class="text-slate-600 dark:text-slate-400 font-medium">{{ $contact->phone }}</div>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 italic">No contact info</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right pr-6">
                                    <a href="{{ route('contacts.edit', $contact) }}" class="text-slate-400 hover:text-[#2563eb] p-1 inline-block"><i class="fas fa-pen"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between text-sm text-slate-500 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-800/50">
                    <div>
                        Showing <span class="font-medium text-slate-900 dark:text-white">{{ $contacts->firstItem() ?? 0 }}</span> to <span class="font-medium text-slate-900 dark:text-white">{{ $contacts->lastItem() ?? 0 }}</span> of <span class="font-medium text-slate-900 dark:text-white">{{ $contacts->total() }}</span> contacts
                    </div>
                    <div class="flex gap-1">
                        @if ($contacts->onFirstPage())
                            <button disabled class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-medium text-sm opacity-50 cursor-not-allowed">Previous</button>
                        @else
                            <a href="{{ $contacts->previousPageUrl() }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-slate-600 transition text-sm">Previous</a>
                        @endif
                        @if ($contacts->hasMorePages())
                            <a href="{{ $contacts->nextPageUrl() }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-slate-600 transition text-sm">Next</a>
                        @else
                            <button disabled class="px-3 py-1.5 border border-slate-200 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-medium text-sm opacity-50 cursor-not-allowed">Next</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
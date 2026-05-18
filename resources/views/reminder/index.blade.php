<x-app-layout>
    <x-slot name="header">Reminders</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-5xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Reminders</h2>
                <a href="{{ route('reminders.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Reminder
                </a>
            </div>

            @if ($reminders->isEmpty())
                <x-empty-state
                    title="No reminders yet"
                    message="Set reminders to keep track of important follow-ups and deadlines."
                    :action="route('reminders.create')"
                    action-label="Create Reminder"
                />
            @else
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($reminders as $reminder)
                            <div class="p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-sm font-semibold flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $reminder->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            Due {{ $reminder->remind_at?->format('M d, Y g:i A') }}
                                            @if ($reminder->remindable)
                                                &middot; {{ class_basename($reminder->remindable_type) }}
                                            @endif
                                        </p>
                                    </div>

                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-400/20',
                                            'sent' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-400/20',
                                            'cancelled' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-400/20',
                                        ];
                                        $statusDots = [
                                            'pending' => 'bg-amber-500',
                                            'sent' => 'bg-emerald-500',
                                            'cancelled' => 'bg-red-500',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $statusColors[$reminder->status] ?? $statusColors['pending'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$reminder->status] ?? $statusDots['pending'] }}"></span>
                                        {{ $reminder->status }}
                                    </span>

                                    @if ($reminder->reminded_at)
                                        <span class="text-xs text-gray-400 dark:text-gray-500 flex-shrink-0">Completed {{ $reminder->reminded_at->diffForHumans() }}</span>
                                    @endif

                                    <div class="flex items-center gap-1 flex-shrink-0" x-data="{ open: false }">
                                        <a href="{{ route('reminders.edit', $reminder) }}" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        @if ($reminder->status === 'pending')
                                            <form method="POST" action="{{ route('reminders.complete', $reminder) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-1.5 text-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <button @click="open = true" class="p-1.5 text-red-400 hover:text-red-600 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        <x-confirm-action-modal name="delete-{{ $reminder->id }}" title="Delete Reminder?" message="This will permanently delete this reminder." :action="route('reminders.destroy', $reminder)" method="delete" button="Delete" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    {{ $reminders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

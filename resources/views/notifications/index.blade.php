<x-app-layout>
    <x-slot name="header">Notifications</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-3xl mx-auto space-y-4">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $notifications->total() }} total</p>
                @if (Auth::user()->unreadNotifications()->count() > 0)
                    <button
                        onclick="fetch(this.dataset.url, { method: 'POST', headers: { 'X-CSRF-TOKEN': this.dataset.token } }).then(() => { document.querySelectorAll('[id^=notification-]').forEach(el => el.classList.remove('bg-emerald-50/50', 'dark:bg-emerald-500/5')); document.querySelectorAll('[data-row]').forEach(el => el.remove()); this.remove(); })"
                        data-url="{{ route('notifications.mark-all-read') }}"
                        data-token="{{ csrf_token() }}"
                        class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline cursor-pointer"
                    >Mark all as read</button>
                @endif
            </div>

            @if ($notifications->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No notifications yet.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($notifications as $notification)
                        <div id="notification-{{ $notification->id }}" class="flex items-start gap-4 px-6 py-4 {{ $notification->read_at ? '' : 'bg-emerald-50/50 dark:bg-emerald-500/5' }}">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold {{ $notification->read_at ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white' }}">
                                            {{ $notification->data['title'] ?? 'Reminder' }}
                                        </p>
                                        @if (!empty($notification->data['description']))
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $notification->data['description'] }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notification->created_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if (!empty($notification->data['url']))
                                            <a href="{{ $notification->data['url'] }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline">{{ $notification->data['url_label'] ?? 'View' }}</a>
                                        @endif
                                        @if (!$notification->read_at)
                                            <button
                                                onclick="fetch(this.dataset.url, { method: 'POST', headers: { 'X-CSRF-TOKEN': this.dataset.token } }).then(() => { document.getElementById(this.dataset.row).classList.remove('bg-emerald-50/50', 'dark:bg-emerald-500/5'); this.remove(); })"
                                                data-url="{{ route('notifications.mark-read', $notification->id) }}"
                                                data-row="notification-{{ $notification->id }}"
                                                data-token="{{ csrf_token() }}"
                                                class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:underline cursor-pointer"
                                            >Mark read</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

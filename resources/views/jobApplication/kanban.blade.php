<x-app-layout>
    <x-slot name="header">Kanban Board</x-slot>

    <div class="p-4 lg:p-6">
        <div class="space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Application Board</h2>
                <div x-show="loading" class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="animate-spin h-4 w-4 text-[#2563eb]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    Saving...
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('job-applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h7m-7-4h7m-3-4h3M4 4h18v18H4V4z"/></svg>
                        List View
                    </a>
                    <a href="{{ route('job-applications.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#2563eb] hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Application
                    </a>
                </div>
            </div>

            @php
                $columns = [
                    ['key' => 'applied', 'label' => 'Applied', 'color' => 'border-t-blue-500'],
                    ['key' => 'in_review', 'label' => 'In Review', 'color' => 'border-t-indigo-500'],
                    ['key' => 'hr_interview', 'label' => 'HR Interview', 'color' => 'border-t-purple-500'],
                    ['key' => 'technical_interview', 'label' => 'Technical Interview', 'color' => 'border-t-orange-500'],
                    ['key' => 'final_interview', 'label' => 'Final Interview', 'color' => 'border-t-pink-500'],
                    ['key' => 'offer', 'label' => 'Offer', 'color' => 'border-t-green-500'],
                    ['key' => 'accepted', 'label' => 'Accepted', 'color' => 'border-t-emerald-500'],
                    ['key' => 'rejected', 'label' => 'Rejected', 'color' => 'border-t-red-500'],
                    ['key' => 'ghosted', 'label' => 'Ghosted', 'color' => 'border-t-gray-500'],
                ];
                $priorityColors = [
                    'low' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                    'normal' => 'bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300',
                    'high' => 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300',
                ];
            @endphp

            <div
                x-data="{
                    draggedId: null,
                    loading: false,
                    dragStart(id) { this.draggedId = id; },
                    dragEnd() { this.draggedId = null; },
                    refreshCounts() {
                        document.querySelectorAll('.kanban-col').forEach(function(col) {
                            var cards = col.querySelectorAll('[draggable]');
                            var badge = col.querySelector('.count-badge');
                            var empty = col.querySelector('.empty-state');
                            if (badge) badge.textContent = cards.length;
                            if (empty) empty.style.display = cards.length === 0 ? 'flex' : 'none';
                        });
                    },
                    async drop(statusValue) {
                        if (!this.draggedId) return;
                        var id = this.draggedId;
                        this.draggedId = null;
                        this.loading = true;
                        var card = document.getElementById('card-' + id);
                        var targetCol = document.getElementById('col-' + statusValue);
                        if (!card || !targetCol) { this.loading = false; return; }
                        var dropZone = targetCol.querySelector('.drop-zone');
                        if (!dropZone) { this.loading = false; return; }
                        dropZone.appendChild(card);
                        card.setAttribute('data-status', statusValue);
                        this.refreshCounts();
                        try {
                            var resp = await fetch('{{ url('/job-applications') }}/' + id + '/updateStatus', {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ status: statusValue })
                            });
                            if (!resp.ok) {
                                var data = await resp.json().catch(() => ({}));
                                showToast(data.message || 'Failed to update status', 'error');
                                this.refreshCounts();
                            }
                        } catch (e) {
                            showToast('Network error. Please try again.', 'error');
                            this.refreshCounts();
                        }
                        this.loading = false;
                    }
                }"
                class="flex gap-4 overflow-x-auto pb-4"
                style="min-height: 70vh;"
            >
                @foreach ($columns as $column)
                    @php
                        $colApps = $applications->get($column['key'], collect());
                        $count = $colApps->count();
                    @endphp

                    <div class="kanban-col flex-shrink-0 w-72 flex flex-col" id="col-{{ $column['key'] }}">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 border-t-4 {{ $column['color'] }} shadow-sm flex flex-col" style="max-height: calc(100vh - 220px);">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $column['label'] }}</h3>
                                    <span class="count-badge inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300">{{ $count }}</span>
                                </div>
                            </div>

                            <div
                                x-data="{ hoverCount: 0 }"
                                class="drop-zone flex-1 overflow-y-auto p-3 space-y-3 transition-colors"
                                style="max-height: calc(100vh - 280px);"
                                @dragover.prevent
                                @dragenter.prevent="hoverCount++"
                                @dragleave.prevent="hoverCount--"
                                @drop.prevent="hoverCount = 0; drop('{{ $column['key'] }}')"
                                :class="hoverCount > 0 ? 'bg-emerald-50 dark:bg-emerald-900/20 ring-2 ring-emerald-300 dark:ring-emerald-600 ring-inset' : ''"
                            >
                                <div class="empty-state flex flex-col items-center justify-center py-8 text-center" style="{{ $count > 0 ? 'display: none;' : '' }}">
                                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Drop here</p>
                                </div>

                                @foreach ($colApps as $app)
                                    <div
                                        id="card-{{ $app->id }}"
                                        data-status="{{ $app->status->value }}"
                                        draggable="true"
                                        @dragstart="dragStart({{ $app->id }})"
                                        @dragend="dragEnd()"
                                        class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-600 transition-all cursor-grab active:cursor-grabbing"
                                    >
                                        <div class="flex items-start justify-between mb-2">
                                            <a href="{{ route('job-applications.show', $app) }}" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 truncate block flex-1 min-w-0 mr-2" onclick="event.stopPropagation()">
                                                {{ $app->job_title }}
                                            </a>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize flex-shrink-0 {{ $priorityColors[$app->priority] ?? $priorityColors['normal'] }}">{{ $app->priority }}</span>
                                        </div>

                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mb-2">{{ $app->company?->name ?? 'No company' }}</p>

                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $app->applied_at?->diffForHumans() ?? 'Not set' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>

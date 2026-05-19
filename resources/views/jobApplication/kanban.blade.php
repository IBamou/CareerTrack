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
                <div class="flex items-center gap-2">
                    <a href="{{ route('job-applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h7m-7-4h7m-3-4h3M4 4h18v18H4V4z"/></svg>
                        List View
                    </a>
                    <a href="{{ route('job-applications.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
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
                    dragStart(id) {
                        this.draggedId = id;
                    },
                    dragEnd() {
                        this.draggedId = null;
                    },
                    drop(statusValue) {
                        if (!this.draggedId) return;
                        $el.querySelector('#status-form-' + this.draggedId + ' input[name=\\'status\\']').value = statusValue;
                        $el.querySelector('#status-form-' + this.draggedId).submit();
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

                    <div class="flex-shrink-0 w-72 flex flex-col">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 border-t-4 {{ $column['color'] }} shadow-sm flex flex-col" style="max-height: calc(100vh - 220px);">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $column['label'] }}</h3>
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300">{{ $count }}</span>
                                </div>
                            </div>

                            <div
                                x-on:dragover.prevent
                                x-on:drop="drop('{{ $column['key'] }}')"
                                class="flex-1 overflow-y-auto p-3 space-y-3"
                                style="max-height: calc(100vh - 280px);"
                            >
                                @if ($count === 0)
                                    <div class="flex flex-col items-center justify-center py-8 text-center">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">No applications</p>
                                    </div>
                                @else
                                    @foreach ($colApps as $app)
                                        <div
                                            draggable="true"
                                            @dragstart="dragStart({{ $app->id }})"
                                            @dragend="dragEnd()"
                                            class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-600 transition-all cursor-grab active:cursor-grabbing"
                                        >
                                            <div class="flex items-start justify-between mb-2">
                                                <a href="{{ route('job-applications.show', $app) }}" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 truncate block flex-1 min-w-0 mr-2">
                                                    {{ $app->job_title }}
                                                </a>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize flex-shrink-0 {{ $priorityColors[$app->priority] ?? $priorityColors['normal'] }}">{{ $app->priority }}</span>
                                            </div>

                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mb-2">{{ $app->company?->name ?? 'No company' }}</p>

                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $app->applied_at?->diffForHumans() ?? 'Not set' }}</span>
                                            </div>

                                            <form id="status-form-{{ $app->id }}" method="POST" action="{{ route('job-applications.updateStatus', $app) }}" class="hidden">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="">
                                            </form>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($applications->flatten()->isEmpty())
                <x-empty-state
                    title="No applications yet"
                    message="Add your first application to start building your kanban board."
                    :action="route('job-applications.create')"
                    action-label="Add Application"
                />
            @endif
        </div>
    </div>
</x-app-layout>

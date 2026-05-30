<x-app-layout>
    <x-slot name="header">Calendar</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto">
            <div
                x-data="calendar({{ $month }}, {{ $year }}, {{ Illuminate\Support\Js::from($events) }}, '{{ date('Y-m-d') }}', {{ Illuminate\Support\Js::from($interviewsList) }}, {{ Illuminate\Support\Js::from($applicationsList) }})"
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm"
            >
                <div class="p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <button @click="prevMonth" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="monthNames[month - 1] + ' ' + year"></h2>
                            <button @click="nextMonth" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                        <button @click="goToToday" class="px-4 py-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg transition-colors">
                            Today
                        </button>
                    </div>

                    <div class="grid grid-cols-7 mb-2">
                        <template x-for="day in dayNames" :key="day">
                            <div class="text-center text-xs font-semibold text-gray-500 dark:text-gray-400 py-2" x-text="day"></div>
                        </template>
                    </div>

                    <div class="grid grid-cols-7">
                        <template x-for="(cell, index) in calendarDays" :key="index">
                            <div
                                @click="openDay(cell)"
                                class="relative min-h-[80px] lg:min-h-[100px] p-1.5 border border-gray-100 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                :class="{
                                    'bg-gray-50 dark:bg-gray-700/30': cell.date === today,
                                    'text-gray-400 dark:text-gray-600': !cell.isCurrentMonth,
                                    'text-gray-900 dark:text-white': cell.isCurrentMonth,
                                }"
                            >
                                <span class="text-sm font-medium" x-text="cell.day"></span>

                                <template x-if="cell.events && cell.events.length > 0">
                                    <div class="mt-1 space-y-0.5">
                                        <template x-for="event in cell.events.slice(0, 3)" :key="event.id + event.type">
                                            <div
                                                class="flex items-center gap-1 px-1 py-0.5 rounded text-xs truncate"
                                                :class="{
                                                    'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300': event.type === 'interview',
                                                    'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300': event.type === 'application',
                                                    'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300': event.type === 'follow_up',
                                                    'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300': event.type === 'reminder',
                                                }"
                                            >
                                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                                    :class="{
                                                        'bg-blue-500': event.type === 'interview',
                                                        'bg-emerald-500': event.type === 'application',
                                                        'bg-amber-500': event.type === 'follow_up',
                                                        'bg-purple-500': event.type === 'reminder',
                                                    }"
                                                ></span>
                                                <span class="truncate" x-text="event.title"></span>
                                            </div>
                                        </template>
                                        <template x-if="cell.events.length > 3">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 px-1" x-text="'+' + (cell.events.length - 3) + ' more'"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div
                    x-show="showModal"
                    @keydown.escape.window="showModal = false"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    style="display: none;"
                >
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showModal = false"></div>
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[80vh] overflow-y-auto">
                        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="formatDate(selectedDate)"></h3>
                            <button @click="showModal = false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <template x-if="selectedDayEvents.length === 0 && !reminderFormOpen">
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No events on this day.</p>
                            </template>

                            <template x-for="event in selectedDayEvents" :key="event.id + event.type">
                                <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <div class="flex-shrink-0 w-2 h-full min-h-[2.5rem] rounded-full mt-1"
                                        :class="{
                                            'bg-blue-500': event.type === 'interview',
                                            'bg-emerald-500': event.type === 'application',
                                            'bg-amber-500': event.type === 'follow_up',
                                            'bg-purple-500': event.type === 'reminder',
                                        }"
                                    ></div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-medium px-1.5 py-0.5 rounded"
                                                :class="{
                                                    'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300': event.type === 'interview',
                                                    'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300': event.type === 'application',
                                                    'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300': event.type === 'follow_up',
                                                    'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300': event.type === 'reminder',
                                                }"
                                                x-text="event.type.replace('_', ' ')"
                                            ></span>
                                            <template x-if="event.time">
                                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="event.time"></span>
                                            </template>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1" x-text="event.title"></p>
                                        <template x-if="event.company">
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="event.company"></p>
                                        </template>
                                        <template x-if="event.description">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="event.description"></p>
                                        </template>
                                        <template x-if="event.remindable_label">
                                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                                                Linked to: <span x-text="event.remindable_label"></span>
                                            </p>
                                        </template>
                                        <div class="flex items-center gap-2 mt-2">
                                            <template x-if="event.url">
                                                <a :href="event.url" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline">View details</a>
                                            </template>
                                            <template x-if="event.type === 'reminder'">
                                                <div class="flex items-center gap-2">
                                                    <button @click="completeReminder(event)" class="text-xs font-medium text-green-600 dark:text-green-400 hover:underline">Mark done</button>
                                                    <button @click="editReminder(event)" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">Edit</button>
                                                    <button @click="deleteReminder(event)" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="!reminderFormOpen" class="pt-2">
                                <button @click="reminderFormOpen = true" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:border-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Reminder
                                </button>
                            </div>

                            <div x-show="reminderFormOpen" style="display: none;">
                                <form @submit.prevent="submitReminder" class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                                        <input type="text" x-model="reminderForm.title" required class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:text-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optional)</label>
                                        <textarea x-model="reminderForm.description" rows="2" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:text-gray-300"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time</label>
                                        <input type="time" x-model="reminderForm.time" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:text-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link to (optional)</label>
                                        <select x-model="reminderForm.remindable_id" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-transparent dark:text-gray-300">
                                            <template x-for="opt in linkableOptions" :key="opt.type + opt.id">
                                                <option :value="opt.id" x-text="opt.label"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-2 pt-1">
                                        <button type="submit" class="px-4 py-2 bg-[#2563eb] hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <span x-show="!editingReminder">Save Reminder</span>
                                            <span x-show="editingReminder">Update Reminder</span>
                                        </button>
                                        <button type="button" @click="cancelEdit" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

<x-app-layout>
    <x-slot name="header">{{ $interview->type?->label() }}</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-5xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <a href="{{ route('interviews.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Interviews
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('interviews.edit', $interview) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <div x-data="{ open: false }">
                        <button type="button" @click="$dispatch('open-modal-archive')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <i class="fas fa-trash text-xs"></i>
                            Archive
                        </button>
                        <x-confirm-action-modal name="archive" title="Archive Interview?" message="This will move the interview to the archive. You can restore it later." :action="route('interviews.archive', $interview)" method="delete" button="Archive" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $interview->type?->label() }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $interview->jobApplication?->company?->name ?? 'No company' }}
                        &middot; {{ $interview->jobApplication?->job_title ?? 'No application' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-section-card title="Interview Details">
                    <dl class="space-y-3">
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Scheduled At</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $interview->scheduled_at?->format('M d, Y g:i A') ?? 'Not set' }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Result</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $interview->result?->label() ?? 'Pending' }}</dd></div>
                    </dl>
                </x-section-card>

                <x-section-card title="Related Application">
                    @if ($interview->jobApplication)
                        <a href="{{ route('job-applications.show', $interview->jobApplication) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $interview->jobApplication->job_title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $interview->jobApplication->company?->name }}</p>
                            </div>
                        </a>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No application linked</p>
                    @endif
                </x-section-card>
            </div>

            @if ($interview->notes)
                <x-section-card title="Notes">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $interview->notes }}</p>
                </x-section-card>
            @endif

            <x-section-card title="Reminders">
                <div x-data="{
                    reminders: {{ Illuminate\Support\Js::from($reminders->map(fn ($r) => [
                        'id' => $r->id,
                        'title' => $r->title,
                        'description' => $r->description,
                        'remind_at' => $r->remind_at->format('Y-m-d H:i'),
                        'status' => $r->status,
                    ])) }},
                    formOpen: false,
                    form: { title: '', description: '', remind_at: '' },

                    interviewId: {{ $interview->id }},
                    submitReminder() {
                        const csrf = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') || '';
                        const payload = {
                            title: this.form.title,
                            description: this.form.description,
                            remind_at: this.form.remind_at || '{{ now()->addDay()->format('Y-m-d 09:00:00') }}',
                            remindable_type: 'App\\Models\\Interview',
                            remindable_id: this.interviewId,
                        };
                        console.log('Sending payload:', JSON.stringify(payload));
                        fetch('/calendar/reminders', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(payload),
                        }).then(r => {
                            if (!r.ok) return r.json().then(e => { throw { response: e, status: r.status }; });
                            return r.json();
                            }).then(data => {
                                if (data.success) {
                                    this.reminders.push({
                                        id: data.reminder.id,
                                        title: data.reminder.title,
                                        description: data.reminder.description,
                                        remind_at: data.reminder.remind_at,
                                        status: 'pending',
                                    });
                                    this.formOpen = false;
                                    this.form = { title: '', description: '', remind_at: '' };
                                    showToast('Reminder added successfully', 'success');
                                }
                            }).catch(err => {
                                if (err.response) {
                                    showToast(err.response.message || 'Failed to save reminder', 'error');
                                } else {
                                    showToast('Network error. Please try again.', 'error');
                                }
                            });
                    },

                    completeReminder(id) {
                        const csrf = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') || '';
                        fetch('/calendar/reminders/' + id + '/complete', {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        }).then(r => {
                            if (r.ok) {
                                this.reminders = this.reminders.filter(r => r.id !== id);
                                showToast('Reminder completed', 'success');
                            } else {
                                showToast('Failed to complete reminder', 'error');
                            }
                        }).catch(() => showToast('Network error. Please try again.', 'error'));
                    },

                    deleteReminder(id) {
                        if (!confirm('Delete this reminder?')) return;
                        const csrf = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') || '';
                        fetch('/calendar/reminders/' + id, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        }).then(r => {
                            if (r.ok) {
                                this.reminders = this.reminders.filter(r => r.id !== id);
                                showToast('Reminder deleted', 'success');
                            } else {
                                showToast('Failed to delete reminder', 'error');
                            }
                        }).catch(() => showToast('Network error. Please try again.', 'error'));
                    },
                }">
                    <template x-if="reminders.length === 0 && !formOpen">
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No reminders for this interview.</p>
                    </template>

                    <template x-for="reminder in reminders" :key="reminder.id">
                        <div class="flex items-center justify-between py-2 px-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="reminder.title"></p>
                                <template x-if="reminder.description">
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="reminder.description"></p>
                                </template>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="reminder.remind_at"></p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                <button @click="completeReminder(reminder.id)" class="text-xs font-medium text-green-600 dark:text-green-400 hover:underline">Mark done</button>
                                <button @click="deleteReminder(reminder.id)" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">Delete</button>
                            </div>
                        </div>
                    </template>

                    <div x-show="!formOpen" class="pt-3">
                        <button @click="formOpen = true" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:border-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Reminder
                        </button>
                    </div>

                    <div x-show="formOpen" style="display: none;" class="pt-3">
                        <form @submit.prevent="submitReminder" class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                                <input type="text" x-model="form.title" required class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-transparent dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optional)</label>
                                <textarea x-model="form.description" rows="2" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-transparent dark:text-gray-300"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Remind at</label>
                                <input type="datetime-local" x-model="form.remind_at" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-transparent dark:text-gray-300">
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="px-4 py-2 bg-[#2563eb] hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">Save Reminder</button>
                                <button type="button" @click="formOpen = false; form = { title: '', description: '', remind_at: '' }" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-section-card>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">New Application</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-3xl mx-auto space-y-6">
            <form method="POST" action="{{ route('job-applications.store') }}" class="space-y-6">
                @csrf

                <x-section-card title="Position Details" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <x-input-label for="job_title" value="Job Title" />
                            <x-text-input id="job_title" name="job_title" type="text" class="mt-1 block w-full" :value="old('job_title')" required placeholder="e.g. Senior Frontend Developer" />
                            <x-input-error :messages="$errors->get('job_title')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="company" value="Company" />
                            <x-company-combobox
                                :companies="$companies"
                                :selected-id="old('company_id')"
                                :selected-name="old('company_id') ? $companies->firstWhere('id', old('company_id'))?->name : ''"
                                :error="$errors->first('company_id') ?? $errors->first('new_company_name')"
                            />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm">
                                @foreach (\App\Enums\JobApplicationStatus::cases() as $s)
                                    <option value="{{ $s->value }}" {{ old('status') === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="priority" value="Priority" />
                            <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm">
                                <option value="low" {{ old('priority', 'normal') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ old('priority', 'normal') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="location_type" value="Location Type" />
                            <select id="location_type" name="location_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm">
                                <option value="">Select...</option>
                                @foreach (\App\Enums\JobLocationType::cases() as $lt)
                                    <option value="{{ $lt->value }}" {{ old('location_type') === $lt->value ? 'selected' : '' }}>{{ $lt->label() }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('location_type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="location_city" value="Location" />
                            <x-text-input id="location_city" name="location_city" type="text" class="mt-1 block w-full" :value="old('location_city')" placeholder="e.g. San Francisco, CA" />
                            <x-input-error :messages="$errors->get('location_city')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="applied_at" value="Applied Date" />
                            <x-text-input id="applied_at" name="applied_at" type="date" class="mt-1 block w-full" :value="old('applied_at')" />
                            <x-input-error :messages="$errors->get('applied_at')" class="mt-2" />
                        </div>
                    </div>
                </x-section-card>

                <x-section-card title="Links" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>'>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="links_job" value="Job Posting URL" />
                            <x-text-input id="links_job" name="links[job_posting]" type="url" class="mt-1 block w-full" :value="old('links.job_posting')" placeholder="https://..." />
                            <x-input-error :messages="$errors->get('links.job_posting')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="links_website" value="Company Website" />
                            <x-text-input id="links_website" name="links[company_website]" type="url" class="mt-1 block w-full" :value="old('links.company_website')" placeholder="https://..." />
                            <x-input-error :messages="$errors->get('links.company_website')" class="mt-2" />
                        </div>
                    </div>
                </x-section-card>

                <x-section-card title="Notes" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'>
                    <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm" placeholder="Any notes about this application...">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </x-section-card>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('job-applications.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Application
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

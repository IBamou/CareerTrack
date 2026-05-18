<x-app-layout>
    <x-slot name="header">New Interview</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-3xl mx-auto space-y-6">
            <form method="POST" action="{{ route('interviews.store') }}" class="space-y-6">
                @csrf

                <x-section-card title="Interview Details" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <x-input-label for="job_application_id" value="Job Application" />
                            <select id="job_application_id" name="job_application_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm" required>
                                <option value="">Select an application...</option>
                                @foreach ($jobApplications as $app)
                                    <option value="{{ $app->id }}" {{ old('job_application_id', request('job_application_id')) == $app->id ? 'selected' : '' }}>{{ $app->job_title }} @if ($app->company) - {{ $app->company->name }} @endif</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('job_application_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" value="Type" />
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm" required>
                                <option value="">Select type...</option>
                                <option value="Phone" {{ old('type') === 'Phone' ? 'selected' : '' }}>Phone</option>
                                <option value="Video Call" {{ old('type') === 'Video Call' ? 'selected' : '' }}>Video Call</option>
                                <option value="HR" {{ old('type') === 'HR' ? 'selected' : '' }}>HR</option>
                                <option value="Technical" {{ old('type') === 'Technical' ? 'selected' : '' }}>Technical</option>
                                <option value="On-site" {{ old('type') === 'On-site' ? 'selected' : '' }}>On-site</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="scheduled_at" value="Scheduled At" />
                            <x-text-input id="scheduled_at" name="scheduled_at" type="datetime-local" class="mt-1 block w-full" :value="old('scheduled_at')" required />
                            <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="result" value="Result" />
                            <select id="result" name="result" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm">
                                <option value="">Pending...</option>
                                <option value="Passed" {{ old('result') === 'Passed' ? 'selected' : '' }}>Passed</option>
                                <option value="Rejected" {{ old('result') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Cancelled" {{ old('result') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Rescheduled" {{ old('result') === 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            </select>
                            <x-input-error :messages="$errors->get('result')" class="mt-2" />
                        </div>
                    </div>
                </x-section-card>

                <x-section-card title="Notes" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'>
                    <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm" placeholder="Preparation notes...">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </x-section-card>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Interview
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

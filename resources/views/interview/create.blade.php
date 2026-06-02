<x-app-layout>
    <x-slot name="header">New Interview</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-3xl mx-auto space-y-6">
            <form method="POST" action="{{ route('interviews.store') }}" class="space-y-6">
                @csrf

                <x-section-card title="Interview Details">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <x-input-label for="job_application_id" value="Job Application" />
                            <select id="job_application_id" name="job_application_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-xl shadow-sm" required>
                                <option value="">Select an application...</option>
                                @foreach ($jobApplications as $app)
                                    <option value="{{ $app->id }}" {{ old('job_application_id', request('job_application_id')) == $app->id ? 'selected' : '' }}>{{ $app->job_title }} @if ($app->company) - {{ $app->company->name }} @endif</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('job_application_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" value="Type" />
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-xl shadow-sm" required>
                                <option value="">Select type...</option>
                                @foreach (App\Enums\InterviewType::cases() as $type)
                                    <option value="{{ $type->value }}" {{ old('type') === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
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
                            <select id="result" name="result" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-xl shadow-sm">
                                <option value="">Pending...</option>
                                @foreach (App\Enums\InterviewResult::cases() as $result)
                                    <option value="{{ $result->value }}" {{ old('result') === $result->value ? 'selected' : '' }}>{{ $result->label() }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('result')" class="mt-2" />
                        </div>
                    </div>
                </x-section-card>

                <x-section-card title="Notes">
                    <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-xl shadow-sm" placeholder="Preparation notes...">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </x-section-card>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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

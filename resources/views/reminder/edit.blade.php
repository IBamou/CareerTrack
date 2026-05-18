<x-app-layout>
    <x-slot name="header">Edit Reminder</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-3xl mx-auto space-y-6">
            <form method="POST" action="{{ route('reminders.update', $reminder) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-section-card title="Reminder Details" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>'>
                    <div class="space-y-5">
                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $reminder->title)" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-xl shadow-sm">{{ old('description', $reminder->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="remind_at" value="Remind At" />
                            <x-text-input id="remind_at" name="remind_at" type="datetime-local" class="mt-1 block w-full" :value="old('remind_at', $reminder->remind_at?->format('Y-m-d\TH:i'))" required />
                            <x-input-error :messages="$errors->get('remind_at')" class="mt-2" />
                        </div>
                    </div>
                </x-section-card>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('reminders.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Update Reminder
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

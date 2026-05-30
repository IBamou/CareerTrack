<x-section-card title="Delete Account">
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl flex items-start gap-3 mb-6">
        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
        <p class="text-sm text-red-700 dark:text-red-300">This action is irreversible. All your job applications, companies, contacts, interviews, documents, reminders, and activity history will be permanently deleted.</p>
    </div>

    <form x-data="{ password: '' }" method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="delete-password" value="Enter your current password to confirm deletion" />
            <div class="mt-1.5 flex items-start gap-3">
                <div class="flex-1">
                    <x-text-input
                        id="delete-password"
                        name="password"
                        type="password"
                        class="block w-full"
                        placeholder="Your password"
                        x-model="password"
                    />
                </div>
                <button type="submit"
                    :disabled="!password.length"
                    :class="password.length ? 'bg-red-600 hover:bg-red-700 cursor-pointer' : 'bg-red-400 cursor-not-allowed'"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm font-medium rounded-lg transition-colors flex-shrink-0">
                    <i class="fas fa-trash-alt text-xs"></i>
                    Delete Account
                </button>
            </div>
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>
    </form>
</x-section-card>

<x-app-layout>
    <x-slot name="header">Profile</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        @if (session('status'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>
                {{ session('status') }}
            </div>
        @endif

        @include('profile.partials.update-profile-information-form')

        @include('profile.partials.update-password-form')

        @include('profile.partials.delete-user-form')
    </div>
</x-app-layout>

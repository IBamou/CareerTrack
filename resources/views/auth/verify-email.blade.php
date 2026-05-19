<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto w-14 h-14 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Verify your email</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we'll gladly send you another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-700 rounded-lg text-sm font-medium text-green-700 dark:text-green-300 text-center">
            A new verification link has been sent to your email.
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center">
                {{ __('Resend verification email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 py-2">
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</x-guest-layout>

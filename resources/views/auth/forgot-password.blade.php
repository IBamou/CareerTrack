<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Reset your password</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Forgot your password? Enter your email and we'll send you a reset link.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Send reset link') }}
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            Remember your password?
            <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Sign in</a>
        </p>
    </form>
</x-guest-layout>

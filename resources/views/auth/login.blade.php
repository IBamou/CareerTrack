<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Sign in to manage your job applications</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-slate-900 border-slate-300 dark:border-slate-600 text-[#2563eb] focus:ring-[#2563eb] dark:focus:ring-blue-500 dark:focus:ring-offset-slate-800" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#2563eb] dark:text-blue-400 hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#2563eb] dark:text-blue-400 hover:underline font-medium">Create one</a>
        </p>
    </form>
</x-guest-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CareerTrack') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white dark:bg-slate-900">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950 opacity-70"></div>

        <header class="relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-briefcase text-[#2563eb] dark:text-blue-400 text-xl"></i>
                        <span class="text-xl font-bold text-slate-900 dark:text-white">{{ config('app.name', 'CareerTrack') }}</span>
                    </div>

                    @if (Route::has('login'))
                        <nav class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#2563eb] border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 transition-colors">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-[#2563eb] border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 transition-colors">
                                        Get started
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </header>

        <main class="relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 lg:pt-24 lg:pb-28">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        Track your
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#2563eb] to-blue-400 dark:from-blue-400 dark:to-blue-300">job applications</span>
                        with ease
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-slate-500 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
                        Organize your job search in one place. Track applications, manage interviews,
                        follow up at the right time, and never lose sight of your opportunities.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-3.5 bg-[#2563eb] border border-transparent rounded-xl font-semibold text-base text-white hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition-all">
                                Go to dashboard
                                <i class="fas fa-arrow-right ml-2 text-sm"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3.5 bg-[#2563eb] border border-transparent rounded-xl font-semibold text-base text-white hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition-all">
                                Start tracking free
                                <i class="fas fa-arrow-right ml-2 text-sm"></i>
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-base text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition-all">
                                Sign in
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="mt-24 lg:mt-32 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="relative p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-clipboard-list text-[#2563eb] dark:text-blue-400 text-lg"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Track applications</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            Log every job you apply to with company details, links, and notes — all in one organized dashboard.
                        </p>
                    </div>

                    <div class="relative p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-chart-bar text-[#2563eb] dark:text-blue-400 text-lg"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Visual progress</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            See your application status at a glance with color-coded badges and a clear progress timeline.
                        </p>
                    </div>

                    <div class="relative p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-check text-[#2563eb] dark:text-blue-400 text-lg"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Manage interviews</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            Keep track of interview schedules, follow-ups, and never miss an important date.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 border-t border-slate-200 dark:border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'CareerTrack') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

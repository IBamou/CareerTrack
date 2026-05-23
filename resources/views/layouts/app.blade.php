<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="font-size: 13px;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CareerTrack') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            [x-cloak] { display: none !important; }
            * { scrollbar-width: thin; scrollbar-color: #CBD5E1 transparent; }
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{ sidebarOpen: false }" class="bg-[#f8fafc] dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-sans h-screen flex overflow-hidden">

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

        <!-- Sidebar (desktop: flex, mobile: fixed overlay) -->
        <x-sidebar class="hidden lg:flex" />
        <x-sidebar class="fixed inset-y-0 left-0 z-50 lg:hidden transition-transform duration-300 ease-in-out" x-bind:class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" />

        <!-- Main content area -->
        <div class="flex flex-col flex-1 min-w-0">

            <!-- Top bar -->
            <header class="sticky top-0 z-30 flex items-center h-16 px-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 lg:px-6">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <div class="flex-1 flex items-center justify-between ml-2 lg:ml-0">
                    <h1 class="text-lg font-bold text-slate-900 dark:text-white">
                        @isset($header)
                            {{ $header }}
                        @else
                            CareerTrack
                        @endisset
                    </h1>

                    <div class="flex items-center gap-3">
                        <!-- Notifications -->
                        @auth
                        <div x-data="{ open: false, notifications: @js(auth()->user()->unreadNotifications->take(10)->values()), count: {{ auth()->user()->unreadNotifications->count() }} }" @keydown.escape.window="open = false" class="relative">
                            <button @click="open = !open" class="relative p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <i class="far fa-bell text-lg"></i>
                                <template x-if="count > 0">
                                    <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full" x-text="count"></span>
                                </template>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 rounded-xl shadow-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-slate-800 overflow-hidden" x-cloak>
                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-slate-900 dark:text-white">Notifications</span>
                                    <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-[#2563eb] dark:text-blue-400 hover:underline">View all</a>
                                </div>
                                <div class="max-h-72 overflow-y-auto">
                                    <template x-if="notifications.length === 0">
                                        <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-6">No notifications</p>
                                    </template>
                                    <template x-for="(n, i) in notifications" :key="n.id">
                                        <a :href="n.data.url || '#'" @click="fetch('/notifications/' + n.id + '/mark-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => { notifications.splice(i, 1); count--; })" class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-b border-slate-50 dark:border-slate-700/50 last:border-0">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                                <i class="fas fa-bell text-blue-600 dark:text-blue-400 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-900 dark:text-white truncate" x-text="n.data.title"></p>
                                                <template x-if="n.data.description">
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="n.data.description"></p>
                                                </template>
                                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5" x-text="new Date(n.created_at).toLocaleDateString()"></p>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                        @endauth

                        <!-- Theme toggle -->
                        <button x-data="{ theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }" @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.classList.toggle('dark', theme === 'dark')" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <i x-show="theme === 'light'" class="fas fa-moon text-lg"></i>
                            <i x-show="theme === 'dark'" class="fas fa-sun text-lg"></i>
                        </button>

                        <!-- User avatar -->
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-[#2563eb] text-white flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-sm text-slate-700 dark:text-slate-300 hidden sm:inline">{{ Auth::user()->name }} <i class="fas fa-chevron-down text-[10px] ml-1 text-slate-400"></i></span>
                            </button>
                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-slate-800 overflow-hidden" x-cloak>
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Profile</a>
                                    <a href="{{ route('job-applications.create') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Add Application</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Log Out</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </body>
</html>
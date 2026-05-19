<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CareerTrack') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <style>
            [x-cloak] { display: none !important; }
            .material-symbols-outlined { font-family: 'Material Symbols Outlined'; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; display: inline-block; line-height: 1; }
            .material-symbols-filled { font-family: 'Material Symbols Outlined'; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; display: inline-block; line-height: 1; }
            #sidebar { width: var(--sidebar-w, 14rem); }
            #main-content { margin-left: var(--sidebar-w, 14rem); }
            #main-content > main { padding: 1.5rem; }
            * { scrollbar-width: thin; scrollbar-color: #CFD8DC transparent; }
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #CFD8DC; border-radius: 3px; }
        </style>

        <script>
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.style.setProperty('--sidebar-w', '3.5rem');
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false, sidebarCollapsed: document.documentElement.style.getPropertyValue('--sidebar-w').trim() === '3.5rem' }" x-init="$watch('sidebarCollapsed', val => { localStorage.setItem('sidebarCollapsed', val); document.documentElement.style.setProperty('--sidebar-w', val ? '3.5rem' : '14rem'); })">
        <div class="flex h-screen overflow-hidden">

            <!-- Mobile sidebar overlay -->
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-900/80 lg:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main content area -->
            <div id="main-content" class="flex flex-col flex-1 min-w-0 transition-all duration-300 ease-in-out">

                <!-- Top bar -->
                <header class="sticky top-0 z-30 flex items-center h-16 px-4 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800 lg:px-6">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="flex-1 flex items-center justify-between ml-2 lg:ml-0">
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                            @isset($header)
                                {{ $header }}
                            @else
                                CareerTrack
                            @endisset
                        </h1>

                        <div class="flex items-center gap-2">
                            <!-- Search -->
                            <form action="{{ route('search') }}" method="GET" class="hidden sm:block">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}" class="w-48 lg:w-64 pl-9 pr-4 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 border-0 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500" />
                                </div>
                            </form>

                            <!-- Theme toggle -->
                            <button x-data="{ theme: localStorage.getItem('theme') || 'light' }" @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.classList.toggle('dark', theme === 'dark')" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </button>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="Log out">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page content -->
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

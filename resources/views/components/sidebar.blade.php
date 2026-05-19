<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 ease-in-out lg:translate-x-0" :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

    <!-- Logo -->
    <div class="relative flex items-center h-16 border-b border-gray-200 dark:border-gray-800" :class="sidebarCollapsed ? 'justify-center px-2' : 'px-4'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 transition-all duration-200" :class="sidebarCollapsed ? 'lg:hidden' : ''">
            <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-lg font-bold text-gray-900 dark:text-white">CareerTrack</span>
        </a>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex items-center justify-center w-6 h-6 rounded-md transition-colors flex-shrink-0" :class="sidebarCollapsed ? 'absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 bg-emerald-600 text-white hover:bg-emerald-700' : 'ml-auto text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'">
            <svg class="w-4 h-4 transition-transform duration-200" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 ml-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Dashboard' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Dashboard</span>
        </a>

        <a href="{{ route('job-applications.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('job-applications.*') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Applications' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Applications</span>
        </a>

        <a href="{{ route('interviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('interviews.*') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Interviews' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Interviews</span>
        </a>

        <div class="space-y-1">
            <a href="{{ route('companies.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('companies.*') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Companies' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Companies</span>
            </a>
            <a href="{{ route('contacts.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('contacts.*') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Contacts' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Contacts</span>
            </a>
            <a href="{{ route('reminders.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('reminders.*') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Reminders' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Reminders</span>
            </a>
            <a href="{{ route('search') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('search') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Search' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Search</span>
            </a>
            <a href="{{ route('archives.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('archives.*') ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''" :title="sidebarCollapsed ? 'Archives' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                <span :class="sidebarCollapsed ? 'lg:hidden' : ''">Archives</span>
            </a>
        </div>
    </nav>

    <!-- User section -->
    <div class="p-3 border-t border-gray-200 dark:border-gray-800">
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-3 w-full px-2 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" :class="sidebarCollapsed ? 'lg:justify-center lg:px-1' : ''">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-xs font-semibold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 text-left min-w-0" :class="sidebarCollapsed ? 'lg:hidden' : ''">
                    <div class="font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</div>
                </div>
            </button>

            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute z-50 w-48 rounded-lg shadow-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-gray-800 overflow-hidden" :class="sidebarCollapsed ? 'left-full ml-1 bottom-0 origin-bottom-left' : 'left-0 bottom-full mb-2 origin-bottom-left'" style="display: none;">
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>

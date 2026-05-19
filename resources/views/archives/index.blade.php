<x-app-layout>
    <x-slot name="header">Archives</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-7xl mx-auto">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Browse and manage archived items across all sections.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('job-applications.archives') }}" class="group p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800 transition-all">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Applications</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Restore or delete archived job applications</p>
                </a>

                <a href="{{ route('companies.archives') }}" class="group p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800 transition-all">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Companies</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Restore or delete archived companies</p>
                </a>

                <a href="{{ route('interviews.archives') }}" class="group p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800 transition-all">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Interviews</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Restore or delete archived interviews</p>
                </a>

                <a href="{{ route('contacts.archives') }}" class="group p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800 transition-all">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Contacts</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Restore or delete archived contacts</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

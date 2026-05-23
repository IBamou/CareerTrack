<x-app-layout>
    <x-slot name="header">Archives</x-slot>

    <div class="max-w-5xl mx-auto">
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Browse and manage archived items across all sections.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('job-applications.archives') }}" class="group p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 transition-all">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-briefcase text-[#2563eb] dark:text-blue-400 text-lg"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-[#2563eb] dark:group-hover:text-blue-400 transition-colors">Applications</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Restore or delete archived job applications</p>
            </a>

            <a href="{{ route('companies.archives') }}" class="group p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 transition-all">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-building text-[#2563eb] dark:text-blue-400 text-lg"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-[#2563eb] dark:group-hover:text-blue-400 transition-colors">Companies</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Restore or delete archived companies</p>
            </a>

            <a href="{{ route('interviews.archives') }}" class="group p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 transition-all">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check text-[#2563eb] dark:text-blue-400 text-lg"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-[#2563eb] dark:group-hover:text-blue-400 transition-colors">Interviews</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Restore or delete archived interviews</p>
            </a>

            <a href="{{ route('contacts.archives') }}" class="group p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-md hover:border-[#2563eb]/30 dark:hover:border-blue-500/30 transition-all">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-friends text-[#2563eb] dark:text-blue-400 text-lg"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-[#2563eb] dark:group-hover:text-blue-400 transition-colors">Contacts</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Restore or delete archived contacts</p>
            </a>
        </div>
    </div>
</x-app-layout>

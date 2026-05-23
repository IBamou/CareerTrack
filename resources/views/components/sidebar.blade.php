<aside
    x-data="{ collapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
    x-init="if (window.innerWidth < 1024) collapsed = false; $watch('collapsed', val => localStorage.setItem('sidebarCollapsed', val))"
    {{ $attributes->merge(['class' => 'bg-white/80 dark:bg-slate-900/80 text-slate-600 dark:text-slate-400 flex flex-col flex-shrink-0 shadow-xl h-full transition-all duration-300']) }}
    x-bind:class="collapsed ? 'w-16' : 'w-56'"
>
    <div class="flex items-center justify-center h-16 flex-shrink-0 transition-all duration-300 overflow-hidden" x-bind:class="collapsed ? 'px-0' : 'px-6'">
        <template x-if="!collapsed">
            <div class="text-2xl font-bold text-slate-900 dark:text-white whitespace-nowrap">
                Career<span class="text-[#2563eb]">Track</span>
            </div>
        </template>
        <template x-if="collapsed">
            <div class="text-2xl font-bold text-slate-900 dark:text-white">
                C<span class="text-[#2563eb]">T</span>
            </div>
        </template>
    </div>

    <nav class="flex-1 space-y-1.5 overflow-y-auto transition-all duration-300" x-bind:class="collapsed ? 'px-2' : 'px-4'">
        <a href="{{ route('dashboard') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="fas fa-home w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Dashboard</span>
        </a>
        <a href="{{ route('job-applications.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('job-applications.*') && !request()->routeIs('job-applications.kanban') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="fas fa-briefcase w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Applications</span>
        </a>
        <a href="{{ route('job-applications.kanban') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('job-applications.kanban') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="fas fa-chart-line w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Track Progress</span>
        </a>
        <a href="{{ route('companies.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('companies.*') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="far fa-building w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Companies</span>
        </a>
        <a href="{{ route('interviews.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('interviews.*') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="fas fa-calendar-check w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Interviews</span>
        </a>
        <a href="{{ route('contacts.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('contacts.*') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="fas fa-user-friends w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Contacts</span>
        </a>
        <a href="{{ route('calendar.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('calendar.*') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="far fa-calendar-alt w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Calendar</span>
        </a>
        <a href="{{ route('search') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('search') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="fas fa-search w-5 text-center flex-shrink-0"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Search</span>
        </a>

        <div x-data="{ moreOpen: false }" class="space-y-1.5">
            <button @click="moreOpen = !moreOpen" class="flex items-center w-full py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
                <i class="fas fa-chevron-down w-5 text-center flex-shrink-0 transition-transform duration-300" x-bind:class="moreOpen ? 'rotate-180' : ''"></i>
                <span x-show="!collapsed" class="whitespace-nowrap">More</span>
            </button>
            <template x-if="!collapsed || moreOpen">
                <div x-show="moreOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="space-y-1.5" x-bind:class="collapsed ? 'px-0' : 'pl-4'">
                    <a href="{{ route('notifications.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('notifications.*') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
                        <i class="far fa-bell w-5 text-center flex-shrink-0"></i>
                        <span x-show="!collapsed" class="whitespace-nowrap">Notifications</span>
                    </a>
                    <a href="{{ route('tags.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('tags.*') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
                        <i class="fas fa-tags w-5 text-center flex-shrink-0"></i>
                        <span x-show="!collapsed" class="whitespace-nowrap">Tags</span>
                    </a>
                    <a href="{{ route('archives.index') }}" class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('archives.*') ? 'bg-[#2563eb] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
                        <i class="fas fa-archive w-5 text-center flex-shrink-0"></i>
                        <span x-show="!collapsed" class="whitespace-nowrap">Archives</span>
                    </a>
                </div>
            </template>
        </div>
    </nav>

    <div class="flex-shrink-0 transition-all duration-300" x-bind:class="collapsed ? 'p-2' : 'p-4'">
        <button @click="collapsed = !collapsed" class="flex items-center w-full py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
            <i class="fas fa-chevron-left w-5 text-center flex-shrink-0 transition-transform duration-300" x-bind:class="collapsed ? 'rotate-180' : ''"></i>
            <span x-show="!collapsed" class="whitespace-nowrap">Collapse</span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full py-2.5 rounded-lg font-medium transition-all duration-300 overflow-hidden text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white mt-1" x-bind:class="collapsed ? 'justify-center px-0' : 'gap-3 px-4'">
                <i class="fas fa-sign-out-alt w-5 text-center flex-shrink-0"></i>
                <span x-show="!collapsed" class="whitespace-nowrap">Log out</span>
            </button>
        </form>
    </div>
</aside>
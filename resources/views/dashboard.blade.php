<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="font-size: 13px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CareerTrack') }} - Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-sans h-screen flex overflow-hidden">

    <x-sidebar />

    <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl text-sm font-medium text-blue-700 dark:text-blue-300 flex items-center gap-2">
                    <i class="fas fa-check-circle text-blue-500"></i>
                    {{ session('status') }}
                </div>
            @endif

            <header class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back, {{ $userName }} 👋</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">Here's what's happening with your applications.</p>
                </div>
                <div class="flex items-center gap-6">
                    <div x-data="{ open: false, notifications: @js(auth()->user()->unreadNotifications->take(10)->values()), count: {{ auth()->user()->unreadNotifications->count() }} }" @keydown.escape.window="open = false" class="relative">
                        <button @click="open = !open" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition relative">
                            <i class="far fa-bell text-xl"></i>
                            <template x-if="count > 0">
                                <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full" x-text="count"></span>
                            </template>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 rounded-xl shadow-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-slate-800 overflow-hidden" style="display: none;">
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
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 cursor-pointer">
                            <div class="w-8 h-8 rounded-full bg-[#2563eb] text-white flex items-center justify-center text-sm font-semibold">
                                {{ $userInitial }}
                            </div>
                            <span class="font-medium text-sm text-slate-700 dark:text-slate-300">{{ $userName }} <i class="fas fa-chevron-down text-[10px] ml-1 text-slate-400"></i></span>
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-slate-800 overflow-hidden" style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-500/10 text-[#2563eb] dark:text-blue-400 flex items-center justify-center text-xl">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total'] }}</div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Applications</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-yellow-50 dark:bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 flex items-center justify-center text-xl">
                        <i class="far fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['in_progress'] }}</div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">In Progress</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-purple-50 dark:bg-purple-500/10 text-purple-500 dark:text-purple-400 flex items-center justify-center text-xl">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['interviews'] }}</div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Interviews</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-50 dark:bg-green-500/10 text-green-500 dark:text-green-400 flex items-center justify-center text-xl">
                        <i class="far fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['offers'] }}</div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Offers</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 dark:text-red-400 flex items-center justify-center text-xl">
                        <i class="far fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['rejections'] }}</div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Rejections</div>
                    </div>
                </div>
            </div>

            @if ($stats['total'] > 0)
            <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_1fr] gap-6">

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Application Progress</h2>
                    <div class="space-y-5">
                        @php
                            $progressItems = [
                                ['icon' => 'fa-check-circle', 'iconColor' => 'text-[#2563eb]', 'label' => 'Applied', 'count' => $stats['applied'], 'color' => 'bg-[#2563eb]', 'total' => $stats['total']],
                                ['icon' => 'fa-circle', 'iconColor' => 'text-[#2563eb]', 'label' => 'Screening', 'count' => $stats['screening'], 'color' => 'bg-[#2563eb]', 'total' => $stats['total']],
                                ['icon' => 'fa-circle', 'iconColor' => 'text-purple-500', 'label' => 'Interview', 'count' => $stats['interviewing'], 'color' => 'bg-purple-500', 'total' => $stats['total']],
                                ['icon' => 'fa-circle', 'iconColor' => 'text-yellow-500', 'label' => 'Offer', 'count' => $stats['offer_count'], 'color' => 'bg-yellow-500', 'total' => $stats['total']],
                                ['icon' => 'fa-check-circle', 'iconColor' => 'text-green-500', 'label' => 'Accepted', 'count' => $stats['offers'] - $stats['offer_count'] >= 0 ? $stats['offers'] - $stats['offer_count'] : 0, 'color' => 'bg-green-500', 'total' => $stats['total']],
                                ['icon' => 'fa-times-circle', 'iconColor' => 'text-red-500', 'label' => 'Rejected', 'count' => $stats['rejected_count'], 'color' => 'bg-red-500', 'total' => $stats['total']],
                            ];
                        @endphp
                        @foreach ($progressItems as $item)
                            <div class="flex items-center gap-4">
                                <i class="fas {{ $item['icon'] }} {{ $item['iconColor'] }} text-xl w-6"></i>
                                <div class="w-24 font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $item['label'] }}</div>
                                <div class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="{{ $item['color'] }} h-full rounded-full" style="width: {{ $item['total'] > 0 ? ($item['count'] / $item['total'] * 100) : 0 }}%"></div>
                                </div>
                                <div class="w-6 text-right text-sm text-slate-500 dark:text-slate-400 font-medium">{{ $item['count'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Recent Applications</h2>
                        <a href="{{ route('job-applications.index') }}" class="text-[#2563eb] dark:text-blue-400 font-semibold text-sm hover:underline">View all</a>
                    </div>
                    <div class="space-y-6 flex-1">
                        @forelse ($recentApplications as $app)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-100 dark:border-slate-600 shadow-sm flex items-center justify-center text-lg font-bold" style="color: {{ $app->company?->color ?? '#64748b' }}">
                                        @if ($app->company)
                                            <span class="text-sm font-bold" style="color: {{ $app->company->color ?? '#64748b' }}">{{ strtoupper(substr($app->company->name, 0, 1)) }}</span>
                                        @else
                                            <i class="far fa-building text-slate-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $app->company?->name ?? 'Unknown Company' }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">{{ $app->job_title }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    @php
                                        $statusClass = match($app->status->value) {
                                            'applied' => 'bg-blue-50 text-[#2563eb] dark:bg-blue-500/10 dark:text-blue-400',
                                            'in_review' => 'bg-orange-50 text-orange-500 dark:bg-orange-500/10 dark:text-orange-400',
                                            'hr_interview', 'technical_interview', 'final_interview' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',
                                            'offer' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'accepted' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
                                            'rejected' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                                            default => 'bg-slate-50 text-slate-500 dark:bg-slate-500/10 dark:text-slate-400',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 {{ $statusClass }} rounded-full text-xs font-bold w-24 text-center">{{ $app->status->label() }}</span>
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 w-24 text-right">{{ $app->created_at?->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                                <i class="far fa-file-alt text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                <p>No applications yet. Start tracking your first one!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_1fr] gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Calendar</h2>
                        <div class="flex items-center gap-2">
                            <button class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <span class="text-sm font-bold text-slate-800 dark:text-slate-200 w-28 text-center">{{ now()->format('F Y') }}</span>
                            <button class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>

                    @php
                        $today = now();
                        $firstDay = $today->copy()->startOfMonth();
                        $lastDay = $today->copy()->endOfMonth();
                        $startOfWeek = $firstDay->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
                        $endOfWeek = $lastDay->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
                        $eventDays = $upcomingEvents->map(fn($e) => $e->scheduled_at?->format('Y-m-d'))->filter()->unique()->toArray();
                    @endphp

                    <div class="grid grid-cols-7 text-center gap-y-2">
                        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider py-2 border-b border-slate-100 dark:border-slate-700">{{ $day }}</div>
                        @endforeach

                        @for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay())
                            @php
                                $isToday = $date->isSameDay($today);
                                $isCurrentMonth = $date->month === $today->month;
                                $hasEvent = in_array($date->format('Y-m-d'), $eventDays);
                            @endphp
                            <div class="flex justify-center">
                                @if ($isToday)
                                    <div class="relative text-sm font-bold text-white bg-[#2563eb] w-8 h-8 rounded-full flex items-center justify-center shadow-sm">
                                        {{ $date->day }}
                                        @if ($hasEvent)
                                            <span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-white rounded-full"></span>
                                        @endif
                                    </div>
                                @else
                                    <div class="relative text-sm font-semibold {{ $isCurrentMonth ? 'text-slate-700 dark:text-slate-300' : 'text-slate-300 dark:text-slate-600' }} w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                        {{ $date->day }}
                                        @if ($hasEvent)
                                            <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-[#2563eb] dark:bg-blue-400 rounded-full"></span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Upcoming Events</h2>
                    <div class="space-y-6">
                        @forelse ($upcomingEvents as $event)
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="w-3 h-3 rounded-full border-2 border-[#2563eb] bg-white dark:bg-slate-800 mt-1.5 flex-shrink-0"></div>
                                    <div>
                                        <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $event->jobApplication?->company?->name ?? 'Interview' }} - {{ $event->type }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">{{ $event->scheduled_at?->format('M d, Y - g:i A') }}</div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-blue-50 text-[#2563eb] dark:bg-blue-500/10 dark:text-blue-400 rounded-full text-xs font-bold">{{ $event->type }}</span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                                <i class="far fa-calendar-check text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                <p>No upcoming events. Schedule your first interview!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @else
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/50 p-6">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-2">Getting Started</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300 mb-4">Start by logging your first job application. Then track your progress as you go through the hiring process.</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex items-start gap-3 p-3 bg-white/70 dark:bg-slate-800/50 rounded-xl">
                        <div class="flex items-center justify-center w-8 h-8 bg-[#2563eb]/10 rounded-lg text-[#2563eb] dark:text-blue-400 text-sm font-bold">1</div>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Log an application</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Just the job title and company</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white/70 dark:bg-slate-800/50 rounded-xl">
                        <div class="flex items-center justify-center w-8 h-8 bg-[#2563eb]/10 rounded-lg text-[#2563eb] dark:text-blue-400 text-sm font-bold">2</div>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Update status</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Track your progress through each stage</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-white/70 dark:bg-slate-800/50 rounded-xl">
                        <div class="flex items-center justify-center w-8 h-8 bg-[#2563eb]/10 rounded-lg text-[#2563eb] dark:text-blue-400 text-sm font-bold">3</div>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Add interviews</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Track your interview schedule</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if ($stats['total'] === 0)
            <form method="POST" action="{{ route('dashboard.quick-add') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 min-w-0">
                        <input type="text" name="job_title" placeholder="Job title (e.g. Frontend Developer)" required class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-transparent dark:text-slate-300 placeholder-slate-400 dark:placeholder-slate-500" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <input type="text" name="company_name" placeholder="Company name" required class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-transparent dark:text-slate-300 placeholder-slate-400 dark:placeholder-slate-500" />
                    </div>
                    <div class="w-full sm:w-auto">
                        <select name="status" class="w-full sm:w-auto px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-transparent dark:text-slate-300">
                            @foreach (\App\Enums\JobApplicationStatus::cases() as $s)
                                <option value="{{ $s->value }}" {{ $s === \App\Enums\JobApplicationStatus::Applied ? 'selected' : '' }}>{{ $s->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#2563eb] hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                            <i class="fas fa-plus"></i>
                            Add
                        </button>
                    </div>
                </div>
            </form>
            @endif

        </div>
    </main>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>
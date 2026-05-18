<x-app-layout>
    <x-slot name="header">{{ $jobApplication->job_title }}</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-5xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Back + actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('job-applications.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Applications
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('job-applications.edit', $jobApplication) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Archive
                        </button>
                        <x-confirm-action-modal name="archive" title="Archive Application?" message="This will move the application to the archive. You can restore it later." :action="route('job-applications.archive', $jobApplication)" method="delete" button="Archive" />
                    </div>
                </div>
            </div>

            <!-- Status + info -->
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 text-white text-xl font-bold">
                    {{ strtoupper(substr($jobApplication->company?->name ?? $jobApplication->job_title, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $jobApplication->job_title }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $jobApplication->company?->name ?? 'No company' }}
                        @if ($jobApplication->location_city)
                            &middot; {{ $jobApplication->location_city }}
                        @endif
                    </p>
                </div>
                <div class="ml-auto">
                    <x-status-badge :status="$jobApplication->status" size="lg" />
                </div>
            </div>

            <!-- Info cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-section-card title="Application Details" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'>
                    <dl class="space-y-3">
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Applied</dt><dd class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $jobApplication->applied_at?->format('M d, Y') ?? 'Not set' }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Priority</dt><dd class="mt-0.5">@php $priorityClasses = ['low' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300', 'normal' => 'bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300', 'high' => 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300']; @endphp<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium capitalize {{ $priorityClasses[$jobApplication->priority] ?? $priorityClasses['normal'] }}">{{ $jobApplication->priority }}</span></dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Location Type</dt><dd class="mt-0.5">{!! $jobApplication->location_type ? '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">' . $jobApplication->location_type->label() . '</span>' : '<span class="text-sm text-gray-500 dark:text-gray-400">Not specified</span>' !!}</dd></div>
                    </dl>
                </x-section-card>

                <x-section-card title="Company" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'>
                    @if ($jobApplication->company)
                        <a href="{{ route('companies.show', $jobApplication->company) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $jobApplication->company->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $jobApplication->company->industry ?? 'No industry' }}</p>
                            </div>
                        </a>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No company linked</p>
                    @endif
                </x-section-card>
            </div>

            <!-- Progress timeline -->
            <x-section-card title="Application Progress" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'>
                <div class="space-y-0">
                    @php
                        $statuses = [
                            ['key' => 'applied', 'label' => 'Applied', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                            ['key' => 'in_review', 'label' => 'In Review', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'],
                            ['key' => 'hr_interview', 'label' => 'HR Interview', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                            ['key' => 'technical_interview', 'label' => 'Technical Interview', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>'],
                            ['key' => 'final_interview', 'label' => 'Final Interview', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>'],
                            ['key' => 'offer', 'label' => 'Offer', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                            ['key' => 'accepted', 'label' => 'Accepted', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                            ['key' => 'rejected', 'label' => 'Rejected', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                            ['key' => 'ghosted', 'label' => 'Ghosted', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>'],
                        ];
                        $statusOrder = ['applied', 'in_review', 'hr_interview', 'technical_interview', 'final_interview', 'offer', 'accepted', 'rejected', 'ghosted'];
                        $currentIndex = array_search($jobApplication->status->value, $statusOrder);
                    @endphp
                    @foreach ($statuses as $index => $step)
                        @php
                            $isActive = $jobApplication->status->value === $step['key'];
                            $isPast = $index <= $currentIndex && !in_array($jobApplication->status->value, ['rejected', 'ghosted']);
                        @endphp
                        <div class="flex items-start gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $isActive ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 ring-2 ring-emerald-500/30' : ($isPast ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-400') }}">
                                    @if ($isPast && !$isActive)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                                    @endif
                                </div>
                                @if (!$loop->last)
                                    <div class="w-px h-8 {{ $isPast ? 'bg-emerald-300 dark:bg-emerald-600' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                @endif
                            </div>
                            <div class="pb-6">
                                <p class="text-sm font-semibold {{ $isActive ? 'text-emerald-600 dark:text-emerald-400' : ($isPast ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500') }}">{{ $step['label'] }}</p>
                                @if ($isActive)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Current status</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-section-card>

            <!-- Notes -->
            @if ($jobApplication->notes)
                <x-section-card title="Notes" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $jobApplication->notes }}</p>
                </x-section-card>
            @endif

            <!-- Links -->
            @if (!empty($jobApplication->links))
                <x-section-card title="Links" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>'>
                    <div class="space-y-2">
                        @if (!empty($jobApplication->links['job_posting']))
                            <a href="{{ $jobApplication->links['job_posting'] }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0 0L10 14"/></svg>
                                Job Posting
                            </a>
                        @endif
                        @if (!empty($jobApplication->links['company_website']))
                            <a href="{{ $jobApplication->links['company_website'] }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                Company Website
                            </a>
                        @endif
                    </div>
                </x-section-card>
            @endif

            <!-- Interviews -->
            <x-section-card title="Interviews" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'>
                <div class="space-y-3">
                    @if ($jobApplication->interviews->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No interviews yet.</p>
                    @else
                        @foreach ($jobApplication->interviews->sortByDesc('scheduled_at') as $interview)
                            <div class="flex items-center justify-between p-3 -mx-4 px-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $interview->type }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $interview->scheduled_at?->format('M d, Y g:i A') }}</p>
                                    @if ($interview->result)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Result: {{ $interview->result }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 ml-4">
                                    <a href="{{ route('interviews.edit', $interview) }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="pt-2">
                        <a href="{{ route('interviews.create', ['job_application_id' => $jobApplication->id]) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Interview
                        </a>
                    </div>
                </div>
            </x-section-card>
        </div>
    </div>
</x-app-layout>

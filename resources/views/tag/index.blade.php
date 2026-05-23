<x-app-layout>
    <x-slot name="header">Tags</x-slot>

    <div class="max-w-3xl mx-auto">

        @if (session('status'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl text-sm font-medium text-blue-700 dark:text-blue-300 flex items-center gap-2">
                <i class="fas fa-check-circle text-blue-500"></i>
                {{ session('status') }}
            </div>
        @endif

        <header class="flex-shrink-0 flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Tags</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Organize your applications, companies, and contacts with tags.</p>
            </div>
        </header>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Create a new tag</h3>
            <form method="POST" action="{{ route('tags.store') }}" class="flex items-center gap-3">
                @csrf
                <div class="flex-1">
                    <input type="text" name="name" placeholder="Tag name" required class="w-full px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none text-sm" />
                </div>
                <div>
                    <input type="color" name="color" value="#2563eb" class="w-10 h-10 rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer bg-transparent p-0.5" />
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2563eb] hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors flex-shrink-0">
                    <i class="fas fa-plus"></i> Add
                </button>
            </form>
        </div>

        @if ($tags->isEmpty())
            <x-empty-state
                title="No tags yet"
                message="Create your first tag above to start organizing."
            />
        @else
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($tags as $tag)
                        <div class="p-4 sm:p-5 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors" x-data="{ editing: false }">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl flex-shrink-0" style="background-color: {{ $tag->color ?? '#2563eb' }}20; color: {{ $tag->color ?? '#2563eb' }}">
                                    <i class="fas fa-tags"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div x-show="!editing">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('tags.show', $tag) }}" class="text-sm font-bold text-slate-900 dark:text-white hover:text-[#2563eb] dark:hover:text-blue-400 transition-colors">{{ $tag->name }}</a>
                                            <span class="inline-block w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $tag->color ?? '#2563eb' }}"></span>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                                            {{ $tag->job_applications_count }} applications
                                            @if ($tag->companies_count || $tag->contacts_count)
                                                &middot; {{ $tag->companies_count }} companies &middot; {{ $tag->contacts_count }} contacts
                                            @endif
                                        </p>
                                    </div>

                                    <form x-show="editing" method="POST" action="{{ route('tags.update', $tag) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $tag->name }}" required class="flex-1 px-3 py-1.5 text-sm rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb] outline-none" />
                                        <input type="color" name="color" value="{{ $tag->color ?? '#2563eb' }}" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer bg-transparent p-0.5" />
                                        <button type="submit" class="p-1.5 text-emerald-500 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" @click="editing = false" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex items-center gap-1 flex-shrink-0" x-show="!editing">
                                    <button @click="editing = true" class="p-1.5 text-slate-400 hover:text-[#2563eb] rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <button type="button" @click="$dispatch('open-modal-delete_tag_{{ $tag->id }}')" class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                    <x-confirm-action-modal name="delete-tag-{{ $tag->id }}" title="Delete Tag?" message='Delete the "{{ $tag->name }}" tag? It will be removed from all linked items.' :action="route('tags.destroy', $tag)" method="delete" button="Delete" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
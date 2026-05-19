<x-app-layout>
    <x-slot name="header">Tags</x-slot>

    <div class="p-4 lg:p-6">
        <div class="max-w-3xl mx-auto space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Manage Tags</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Organize your applications, companies, and contacts with tags.</p>
                </div>
            </div>

            <!-- Create tag form -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Create a new tag</h3>
                <form method="POST" action="{{ route('tags.store') }}" class="flex items-center gap-3">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="name" placeholder="Tag name" required class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 focus:border-transparent dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500" />
                    </div>
                    <div>
                        <input type="color" name="color" value="#0891b2" class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer bg-transparent p-0.5" />
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add
                    </button>
                </form>
            </div>

            @if ($tags->isEmpty())
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No tags yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your first tag above to start organizing.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($tags as $tag)
                            <div class="p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" x-data="{ editing: false }">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-xl flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}20; color: {{ $tag->color ?? '#0891b2' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div x-show="!editing">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tag->name }}</span>
                                                <span class="inline-block w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $tag->color ?? '#0891b2' }}"></span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $tag->job_applications_count }} applications
                                                @if ($tag->companies_count || $tag->contacts_count)
                                                    &middot; {{ $tag->companies_count }} companies &middot; {{ $tag->contacts_count }} contacts
                                                @endif
                                            </p>
                                        </div>

                                        <form x-show="editing" method="POST" action="{{ route('tags.update', $tag) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $tag->name }}" required class="flex-1 px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 focus:border-transparent dark:text-gray-300" />
                                            <input type="color" name="color" value="{{ $tag->color ?? '#0891b2' }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer bg-transparent p-0.5" />
                                            <button type="submit" class="p-1.5 text-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                            <button type="button" @click="editing = false" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex items-center gap-1 flex-shrink-0" x-show="!editing">
                                        <button @click="editing = true" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('tags.destroy', $tag) }}" onsubmit="return confirm('Delete the &quot;{{ addslashes($tag->name) }}&quot; tag? It will be removed from all linked items.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

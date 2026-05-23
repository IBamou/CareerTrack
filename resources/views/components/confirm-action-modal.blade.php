@props(['name', 'title', 'message', 'action', 'method' => 'delete', 'button' => 'Confirm'])

<x-modal :name="$name" maxWidth="sm">
    <form method="post" action="{{ $action }}">
        @csrf
        @method($method)

        <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
        <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">{{ $message }}</p>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" x-on:click="$dispatch('close-modal-{{ str_replace('-', '_', $name) }}')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                {{ $button }}
            </button>
        </div>
    </form>
</x-modal>

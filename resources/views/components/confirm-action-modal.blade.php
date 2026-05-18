<x-modal name="{{ $name }}" focusable>
    <form method="post" action="{{ $action }}" class="p-6">
        @csrf
        @method($method)

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $title }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $message }}
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                Cancel
            </x-secondary-button>

            <x-danger-button type="submit">
                {{ $button }}
            </x-danger-button>
        </div>
    </form>
</x-modal>

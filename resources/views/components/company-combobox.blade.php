@props(['companies' => [], 'selectedId' => null, 'selectedName' => '', 'name' => 'company_id', 'error' => null])

@php
$companiesJson = $companies->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()->toJson();
@endphp

<div
    x-data="{
        open: false,
        search: '{{ $selectedName }}',
        selectedId: '{{ $selectedId }}',
        companies: {{ $companiesJson }},
        get filtered() {
            if (!this.search) return this.companies;
            const s = this.search.toLowerCase();
            return this.companies.filter(c => c.name.toLowerCase().includes(s));
        },
        get hasExactMatch() {
            return this.companies.some(c => c.name.toLowerCase() === this.search.toLowerCase());
        },
        select(company) {
            this.search = company.name;
            this.selectedId = company.id;
            this.open = false;
            $refs.companyId.value = company.id;
            $refs.newCompanyName.value = '';
        },
        addNew() {
            this.open = false;
            $refs.companyId.value = '';
            $refs.newCompanyName.value = this.search;
        },
        clear() {
            this.search = '';
            this.selectedId = '';
            $refs.companyId.value = '';
            $refs.newCompanyName.value = '';
        }
    }"
    @click.away="open = false"
    class="relative"
>
    <input
        type="text"
        x-model="search"
        @focus="open = true"
        @input="open = true"
        @keydown.enter.prevent="filtered.length === 1 ? select(filtered[0]) : (filtered.length === 0 && search ? addNew() : null)"
        @keydown.escape="open = false"
        placeholder="Search or type a new company..."
        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-lg shadow-sm"
        autocomplete="off"
    />

    <input type="hidden" name="{{ $name }}" x-ref="companyId" value="{{ $selectedId }}" />
    <input type="hidden" name="new_company_name" x-ref="newCompanyName" value="" />

    @if ($error)
        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $error }}</p>
    @endif

    <div
        x-show="open && search.length > 0"
        x-cloak
        class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
    >
        <template x-for="company in filtered" :key="company.id">
            <button
                type="button"
                @click="select(company)"
                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-left hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors"
                :class="selectedId == company.id ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'"
            >
                <div class="flex items-center justify-center w-7 h-7 rounded-md bg-gradient-to-br from-emerald-400 to-teal-500 text-white text-xs font-semibold flex-shrink-0">
                    <span x-text="company.name.charAt(0).toUpperCase()"></span>
                </div>
                <span x-text="company.name"></span>
            </button>
        </template>

        <template x-if="!hasExactMatch && search.trim().length > 0">
            <button
                type="button"
                @click="addNew"
                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-left text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 border-t border-gray-100 dark:border-gray-700 transition-colors font-medium"
            >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add "<span x-text="search"></span>"</span>
            </button>
        </template>

        <template x-if="filtered.length === 0 && !hasExactMatch">
            <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">
                No matches found
            </div>
        </template>
    </div>
</div>

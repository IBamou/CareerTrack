@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-800', 'dropdown_classes' => ''])

@php
$alignmentClasses = match ($align) {
    'left' => 'origin-top-left left-0',
    'top-start' => 'origin-bottom-left left-0 bottom-full mb-2',
    'top-end' => 'origin-bottom-right right-0 bottom-full mb-2',
    'bottom-start' => 'origin-top-left left-0 top-full mt-2',
    'bottom-end' => 'origin-top-right right-0 top-full mt-2',
    default => 'origin-top-right right-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 {{ $width }} rounded-lg shadow-lg ring-1 ring-black/5 dark:ring-white/10 {{ $alignmentClasses }} {{ $dropdown_classes }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-lg {{ $contentClasses }} overflow-hidden">
            {{ $content }}
        </div>
    </div>
</div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 {{ $width }} rounded-lg shadow-lg ring-1 ring-black/5 dark:ring-white/10 {{ $alignmentClasses }} {{ $dropdownClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-lg {{ $contentClasses }} overflow-hidden">
            {{ $content }}
        </div>
    </div>
</div>

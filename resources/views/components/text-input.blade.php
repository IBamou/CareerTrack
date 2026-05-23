@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 focus:border-[#2563eb] dark:focus:border-blue-500 focus:ring-[#2563eb] dark:focus:ring-blue-500 rounded-lg shadow-sm text-sm']) }}>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CareerTrack') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f8fafc] dark:bg-slate-900 text-slate-800 dark:text-slate-200">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-white to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950">
        <div class="mb-6">
            <a href="/" class="flex items-center gap-2">
                <i class="fas fa-briefcase text-[#2563eb] dark:text-blue-400 text-2xl"></i>
                <span class="text-xl font-bold text-slate-900 dark:text-white">{{ config('app.name', 'CareerTrack') }}</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-6 py-6 bg-white dark:bg-slate-800 shadow-lg shadow-blue-100/50 dark:shadow-black/20 sm:rounded-xl border border-slate-200 dark:border-slate-700">
            {{ $slot }}
        </div>
    </div>
</body>
</html>

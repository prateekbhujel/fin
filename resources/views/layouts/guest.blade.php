<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-gray-950">
    <div class="grid min-h-screen lg:grid-cols-[1.1fr_0.9fr]">
        <div class="relative hidden overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(64,92,245,0.35),_transparent_42%),linear-gradient(135deg,#0f172a_0%,#111827_100%)] p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.04)_1px,transparent_1px)] bg-[size:42px_42px] opacity-30"></div>
            <div class="relative">
                <div class="inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-sm font-medium">
                    {{ setting('app.company_name', 'Nepali Finance Management System') }}
                </div>
                <h1 class="mt-8 max-w-xl text-4xl font-semibold leading-tight">
                    Clean finance operations for Nepali offices, teams, and daily reporting.
                </h1>
                <p class="mt-4 max-w-lg text-sm text-white/70">
                    Track income, expenses, reports, documents, and announcements from a single Laravel dashboard.
                </p>
            </div>

            <div class="relative grid max-w-lg gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <div class="text-sm text-white/70">AD + BS support</div>
                    <div class="mt-2 text-xl font-semibold">Date-aware reporting</div>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <div class="text-sm text-white/70">Shared hosting ready</div>
                    <div class="mt-2 text-xl font-semibold">No queue workers required</div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center bg-gray-50 px-6 py-10 dark:bg-gray-950">
            <div class="w-full max-w-md rounded-[2rem] border border-gray-200 bg-white p-8 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>

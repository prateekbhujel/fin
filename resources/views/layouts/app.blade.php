@php($menuGroups = \App\Support\Navigation\MenuBuilder::groups(auth()->user()))
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($title ?? trim($__env->yieldContent('title')) ?: config('app.name')) }}</title>
    <script>
        (() => {
            const theme = localStorage.getItem('finance-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    x-data="{
        sidebarOpen: false,
        sidebarExpanded: window.innerWidth >= 1280,
        darkMode: document.documentElement.classList.contains('dark'),
        init() {
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1280) {
                    this.sidebarOpen = false;
                    this.sidebarExpanded = true;
                } else {
                    this.sidebarExpanded = false;
                }
            });
        },
        toggleSidebar() {
            if (window.innerWidth >= 1280) {
                this.sidebarExpanded = !this.sidebarExpanded;
            } else {
                this.sidebarOpen = !this.sidebarOpen;
            }
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            document.documentElement.classList.toggle('dark', this.darkMode);
            localStorage.setItem('finance-theme', this.darkMode ? 'dark' : 'light');
        },
    }"
    class="min-h-full bg-gray-50 dark:bg-gray-950"
>
    <div class="flex min-h-screen">
        @include('layouts.partials.sidebar', ['menuGroups' => $menuGroups])

        <div class="flex min-h-screen flex-1 flex-col transition-all duration-300" :class="sidebarExpanded ? 'xl:pl-[18rem]' : 'xl:pl-[6rem]'">
            @include('layouts.partials.header')

            <main class="page-shell">
                @if (session('status'))
                    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4 rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-700 dark:border-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                        {{ session('warning') }}
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

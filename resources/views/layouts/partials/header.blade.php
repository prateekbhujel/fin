<header class="sticky top-0 z-20 border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-800 dark:bg-gray-950/90">
    <div class="page-shell flex items-center justify-between py-4">
        <div class="flex items-center gap-3">
            <button class="rounded-xl border border-gray-200 bg-white p-2.5 text-gray-600 shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200" @click="toggleSidebar()">
                <span class="sr-only">Toggle sidebar</span>
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none">
                    <path d="M4 7h16M4 12h16M4 17h10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
            </button>

            <div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ setting('app.company_name', 'Daily Finance Desk') }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button class="rounded-xl border border-gray-200 bg-white p-2.5 text-gray-600 shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200" @click="toggleTheme()">
                <svg x-show="!darkMode" viewBox="0 0 24 24" class="h-5 w-5" fill="none">
                    <path d="M12 3v2.5m0 13V21m9-9h-2.5M5.5 12H3m14.86 6.36-1.77-1.77M7.91 7.91 6.14 6.14m11.72 0-1.77 1.77M7.91 16.09l-1.77 1.77M15.5 12a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
                <svg x-show="darkMode" viewBox="0 0 24 24" class="h-5 w-5" fill="none">
                    <path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="hidden rounded-2xl border border-gray-200 bg-white px-4 py-2 text-right shadow-sm dark:border-gray-800 dark:bg-gray-900 md:block">
                <div class="text-xs uppercase tracking-[0.2em] text-gray-400">{{ auth()->user()?->designation ?? 'User' }}</div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()?->name }}</div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn-secondary" type="submit">Logout</button>
            </form>
        </div>
    </div>
</header>

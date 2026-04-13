<aside
    class="fixed inset-y-0 left-0 z-40 flex flex-col border-r border-gray-200 bg-white/95 px-4 py-6 backdrop-blur transition-all duration-300 dark:border-gray-800 dark:bg-gray-950/95 xl:translate-x-0"
    :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', sidebarExpanded ? 'xl:w-72' : 'xl:w-24']"
    x-cloak
>
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-500 text-lg font-semibold text-white shadow-lg shadow-brand-500/30">
                N
            </div>
            <div x-show="sidebarExpanded || window.innerWidth < 1280" x-cloak>
                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ setting('app.company_name', 'Finance System') }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Blade + TailAdmin UI</div>
            </div>
        </a>
        <button class="rounded-xl p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 xl:hidden" @click="sidebarOpen = false">
            <span class="sr-only">Close sidebar</span>
            &times;
        </button>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto pr-2">
        @foreach ($menuGroups as $group)
            <div>
                <div class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.24em] text-gray-400" x-show="sidebarExpanded || window.innerWidth < 1280" x-cloak>
                    {{ $group['title'] }}
                </div>
                <div class="space-y-1">
                    @foreach ($group['items'] as $item)
                        @php($active = request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'))
                        <a
                            href="{{ route($item['route']) }}"
                            class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium transition"
                            @class([
                                'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300' => $active,
                                'text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800' => ! $active,
                            ])
                        >
                            <span class="shrink-0">{!! \App\Support\Navigation\MenuBuilder::icon($item['icon']) !!}</span>
                            <span x-show="sidebarExpanded || window.innerWidth < 1280" x-cloak>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="mt-6 rounded-3xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Signed in as</div>
        <div class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()?->name }}</div>
        <div class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()?->getRoleNames()->implode(', ') }}</div>
    </div>
</aside>

<div
    class="fixed inset-0 z-30 bg-gray-900/50 xl:hidden"
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    x-cloak
></div>

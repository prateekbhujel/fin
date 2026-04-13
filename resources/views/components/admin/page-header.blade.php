@props(['title', 'description' => null])

<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>

    @if (trim($slot))
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>

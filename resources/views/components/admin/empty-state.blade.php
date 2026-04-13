@props(['title', 'description'])

<div class="card p-8 text-center">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
    @if (trim($slot))
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>

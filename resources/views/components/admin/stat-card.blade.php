@props(['label', 'value', 'tone' => 'brand'])

@php
    $tones = [
        'brand' => 'from-brand-500/15 to-brand-100/40 text-brand-600 dark:from-brand-500/20 dark:to-brand-500/5 dark:text-brand-300',
        'success' => 'from-emerald-500/15 to-emerald-100/40 text-emerald-600 dark:from-emerald-500/20 dark:to-emerald-500/5 dark:text-emerald-300',
        'warning' => 'from-orange-500/15 to-orange-100/40 text-orange-600 dark:from-orange-500/20 dark:to-orange-500/5 dark:text-orange-300',
    ];
@endphp

<div class="card p-5">
    <div class="inline-flex rounded-xl bg-gradient-to-br px-3 py-1.5 text-xs font-semibold {{ $tones[$tone] ?? $tones['brand'] }}">
        {{ $label }}
    </div>
    <div class="mt-4 text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</div>
</div>

<?php

use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use App\Support\Settings\SettingsBag;
use Carbon\Carbon;

if (! function_exists('npr')) {
    function npr(float|int|string|null $amount, ?string $symbol = null): string
    {
        $symbol ??= setting('app.currency_symbol', 'NPR');

        return sprintf('%s %s', $symbol, number_format((float) ($amount ?? 0), 2));
    }
}

if (! function_exists('bs_date')) {
    function bs_date(\DateTimeInterface|string|null $date, string $format = 'Y-m-d', string $locale = 'en'): ?string
    {
        if (blank($date)) {
            return null;
        }

        try {
            $adDate = $date instanceof \DateTimeInterface
                ? $date->format('Y-m-d')
                : Carbon::parse($date)->format('Y-m-d');

            return LaravelNepaliDate::from($adDate)->toNepaliDate(format: $format, locale: $locale);
        } catch (\Throwable) {
            return null;
        }
    }
}

if (! function_exists('ad_date_from_bs')) {
    function ad_date_from_bs(?string $date, string $format = 'Y-m-d'): ?string
    {
        if (blank($date)) {
            return null;
        }

        try {
            return LaravelNepaliDate::from($date, $format, 'np')->toEnglishDate();
        } catch (\Throwable) {
            return null;
        }
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingsBag::class)->get($key, $default);
    }
}

if (! function_exists('setting_bool')) {
    function setting_bool(string $key, bool $default = false): bool
    {
        return app(SettingsBag::class)->bool($key, $default);
    }
}

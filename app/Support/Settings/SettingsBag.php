<?php

namespace App\Support\Settings;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingsBag
{
    protected ?Collection $items = null;

    public function all(): Collection
    {
        return $this->items ??= Setting::query()->pluck('value', 'key');
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()->get($key, $default);
    }

    public function bool(string $key, bool $default = false): bool
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default;
    }

    public function flush(): void
    {
        $this->items = null;
    }
}

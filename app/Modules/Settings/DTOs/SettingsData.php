<?php

namespace App\Modules\Settings\DTOs;

use Illuminate\Support\Collection;

readonly class SettingsData
{
    public function __construct(
        public array $groups,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function rows(): Collection
    {
        return collect($this->groups)
            ->flatMap(function (array $groupSettings, string $group) {
                return collect($groupSettings)->map(function ($value, string $key) use ($group) {
                    return [
                        'group' => $group,
                        'key' => $group.'.'.$key,
                        'value' => is_bool($value) ? (string) (int) $value : (string) $value,
                        'type' => is_bool($value) ? 'boolean' : 'string',
                    ];
                });
            })
            ->values();
    }
}

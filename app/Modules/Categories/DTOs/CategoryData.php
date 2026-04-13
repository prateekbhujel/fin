<?php

namespace App\Modules\Categories\DTOs;

readonly class CategoryData
{
    public function __construct(
        public string $name,
        public string $type,
        public ?string $description,
        public bool $isActive,
    ) {
    }

    public static function fromArray(array $data, bool $isActive): self
    {
        return new self(
            name: trim((string) $data['name']),
            type: (string) $data['type'],
            description: filled($data['description'] ?? null) ? (string) $data['description'] : null,
            isActive: $isActive,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'is_active' => $this->isActive,
        ];
    }
}

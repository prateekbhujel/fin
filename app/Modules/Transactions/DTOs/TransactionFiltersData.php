<?php

namespace App\Modules\Transactions\DTOs;

readonly class TransactionFiltersData
{
    public function __construct(
        public ?string $search = null,
        public ?string $type = null,
        public ?int $categoryId = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?string $fromBs = null,
        public ?string $toBs = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            search: filled($data['search'] ?? null) ? (string) $data['search'] : null,
            type: filled($data['type'] ?? null) ? (string) $data['type'] : null,
            categoryId: filled($data['category_id'] ?? null) ? (int) $data['category_id'] : null,
            dateFrom: filled($data['date_from'] ?? null) ? (string) $data['date_from'] : null,
            dateTo: filled($data['date_to'] ?? null) ? (string) $data['date_to'] : null,
            fromBs: filled($data['from_bs'] ?? null) ? (string) $data['from_bs'] : null,
            toBs: filled($data['to_bs'] ?? null) ? (string) $data['to_bs'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'search' => $this->search,
            'type' => $this->type,
            'category_id' => $this->categoryId,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'from_bs' => $this->fromBs,
            'to_bs' => $this->toBs,
        ], static fn (mixed $value) => ! blank($value));
    }
}

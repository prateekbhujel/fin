<?php

namespace App\Modules\Documents\DTOs;

readonly class DocumentFiltersData
{
    public function __construct(
        public ?string $search = null,
        public ?int $transactionId = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            search: filled($data['search'] ?? null) ? (string) $data['search'] : null,
            transactionId: filled($data['transaction_id'] ?? null) ? (int) $data['transaction_id'] : null,
        );
    }
}

<?php

namespace App\Modules\Documents\DTOs;

readonly class DocumentUploadData
{
    public function __construct(
        public ?int $transactionId,
        public ?string $description,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            transactionId: filled($data['transaction_id'] ?? null) ? (int) $data['transaction_id'] : null,
            description: filled($data['description'] ?? null) ? (string) $data['description'] : null,
        );
    }
}

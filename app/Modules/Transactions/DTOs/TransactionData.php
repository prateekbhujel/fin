<?php

namespace App\Modules\Transactions\DTOs;

readonly class TransactionData
{
    public function __construct(
        public ?int $categoryId,
        public string $title,
        public string $type,
        public float $amount,
        public string $transactionDate,
        public ?string $transactionDateBs,
        public string $paymentMethod,
        public ?string $referenceNo,
        public ?string $notes,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            categoryId: filled($data['category_id'] ?? null) ? (int) $data['category_id'] : null,
            title: trim((string) $data['title']),
            type: (string) $data['type'],
            amount: (float) $data['amount'],
            transactionDate: (string) $data['transaction_date'],
            transactionDateBs: filled($data['transaction_date_bs'] ?? null) ? (string) $data['transaction_date_bs'] : null,
            paymentMethod: (string) $data['payment_method'],
            referenceNo: filled($data['reference_no'] ?? null) ? (string) $data['reference_no'] : null,
            notes: filled($data['notes'] ?? null) ? (string) $data['notes'] : null,
        );
    }

    public function toArray(int $userId): array
    {
        return [
            'user_id' => $userId,
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'type' => $this->type,
            'amount' => $this->amount,
            'transaction_date' => $this->transactionDate,
            'transaction_bs' => $this->transactionDateBs ?: bs_date($this->transactionDate),
            'payment_method' => $this->paymentMethod,
            'reference_no' => $this->referenceNo,
            'notes' => $this->notes,
        ];
    }
}

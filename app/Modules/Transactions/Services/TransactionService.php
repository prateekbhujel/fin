<?php

namespace App\Modules\Transactions\Services;

use App\Mail\TransactionCreatedMail;
use App\Models\Category;
use App\Models\Document;
use App\Models\Transaction;
use App\Modules\Transactions\DTOs\TransactionData;
use App\Modules\Transactions\DTOs\TransactionFiltersData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    public function paginate(TransactionFiltersData $filters, int $perPage = 12): LengthAwarePaginator
    {
        return Transaction::query()
            ->with(['category', 'user'])
            ->filter($filters->toArray())
            ->latest('transaction_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function categories(bool $onlyActive = false): Collection
    {
        return Category::query()
            ->when($onlyActive, fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->get();
    }

    public function types(): array
    {
        return config('finance.transaction_types');
    }

    public function paymentMethods(): array
    {
        return config('finance.payment_methods');
    }

    public function store(TransactionData $data, int $ownerUserId, int $actorUserId, array $documents = []): Transaction
    {
        $transaction = $this->persist(new Transaction, $data, $ownerUserId);
        $this->storeDocuments($transaction, $documents, $actorUserId);
        $this->sendNotificationIfEnabled($transaction);

        return $transaction;
    }

    public function update(Transaction $transaction, TransactionData $data, int $actorUserId, array $documents = []): Transaction
    {
        $transaction = $this->persist($transaction, $data, (int) $transaction->user_id);
        $this->storeDocuments($transaction, $documents, $actorUserId);

        return $transaction;
    }

    public function delete(Transaction $transaction): void
    {
        foreach ($transaction->documents as $document) {
            Storage::disk($document->disk)->delete($document->path);
            $document->delete();
        }

        $transaction->delete();
    }

    protected function persist(Transaction $transaction, TransactionData $data, int $userId): Transaction
    {
        $category = $data->categoryId ? Category::find($data->categoryId) : null;

        if ($category && $category->type !== $data->type) {
            throw ValidationException::withMessages([
                'category_id' => 'Selected category does not match the transaction type.',
            ]);
        }

        $transaction->fill($data->toArray($userId))->save();

        return $transaction->refresh();
    }

    protected function storeDocuments(Transaction $transaction, array $files, int $userId): void
    {
        foreach ($files as $file) {
            $path = $file->store('documents', 'public');

            Document::create([
                'transaction_id' => $transaction->id,
                'user_id' => $userId,
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => basename($path),
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }

    protected function sendNotificationIfEnabled(Transaction $transaction): void
    {
        if (! setting_bool('mail.send_transaction_notifications', false)) {
            return;
        }

        $recipient = setting('mail.notification_email');

        if (! filled($recipient)) {
            return;
        }

        Mail::to($recipient)->send(new TransactionCreatedMail($transaction->load(['category', 'user'])));

        $transaction->update(['email_notified' => true]);
    }
}

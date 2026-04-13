<?php

namespace App\Modules\Documents\Services;

use App\Models\Document;
use App\Models\Transaction;
use App\Modules\Documents\DTOs\DocumentFiltersData;
use App\Modules\Documents\DTOs\DocumentUploadData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentService
{
    public function paginate(DocumentFiltersData $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Document::query()->with(['transaction', 'user'])->latest();

        if ($filters->search) {
            $query->where('original_name', 'like', "%{$filters->search}%");
        }

        if ($filters->transactionId) {
            $query->where('transaction_id', $filters->transactionId);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function recentTransactions(int $limit = 50): Collection
    {
        return Transaction::query()
            ->latest('transaction_date')
            ->take($limit)
            ->get();
    }

    public function upload(DocumentUploadData $data, array $files, int $userId): void
    {
        foreach ($files as $file) {
            $path = $file->store('documents', 'public');

            Document::create([
                'transaction_id' => $data->transactionId,
                'user_id' => $userId,
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => basename($path),
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'description' => $data->description,
            ]);
        }
    }

    public function delete(Document $document): void
    {
        Storage::disk($document->disk)->delete($document->path);
        $document->delete();
    }

    public function download(Document $document): StreamedResponse
    {
        return Storage::disk($document->disk)->download($document->path, $document->original_name);
    }
}
